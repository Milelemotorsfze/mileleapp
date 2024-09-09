<div class="modal fade" id="updatevehModiStatusModal_{{$vehicle->id}}" tabindex="-1" aria-labelledby="updateStatusModalLabel_{{$vehicle->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel_{{$vehicle->id}}">Update Vehicle Modification Status</h5></br>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="vehModiStatusForm_{{$vehicle->id}}">               
                    <div class="mb-3 mt-3 d-flex justify-content-between">
                        <p>Work Order : <strong>{{$workOrder->wo_number ?? ''}}</strong></p>
                        <p>VIN : <strong>{{$vehicle->vin ?? ''}}</strong></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehModiStatus_{{$vehicle->id}}" id="vehModiStatusNotInitiated_{{$vehicle->id}}" value="Not Initiated" {{ $vehicle->modification_status == 'Not Initiated' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusNotInitiated_{{$vehicle->id}}" style="font-size: 14px;">
                                Not Initiated
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehModiStatus_{{$vehicle->id}}" id="vehModiStatusInitiated_{{$vehicle->id}}" value="Initiated" {{ $vehicle->modification_status == 'Initiated' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusInitiated_{{$vehicle->id}}" style="font-size: 14px;">
                                Initiated
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehModiStatus_{{$vehicle->id}}" id="vehModiStatusCompleted_{{$vehicle->id}}" value="Completed" {{ $vehicle->modification_status == 'Completed' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusCompleted_{{$vehicle->id}}" style="font-size: 14px;">
                                Completed
                            </label>
                        </div>
                    </div>
                    
                    <!-- Expected Completion Date and Time (Only for "Initiated") -->
                    <div class="mb-3 mt-3" id="expectedCompletionSection_{{$vehicle->id}}" style="display: none;">
                        <label for="expectedCompletion_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Expected Completion Date and Time:</label>
                        <input type="datetime-local" class="form-control" id="expectedCompletion_{{$vehicle->id}}" name="expectedCompletion" style="font-size: 14px;">
                    </div>

                    <!-- Current Vehicle Location (Only for "Initiated") -->
                    <div class="mb-3" id="currentVehicleLocationSection_{{$vehicle->id}}" style="display: none;">
                        <label for="currentVehicleLocation_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Current Vehicle Location:</label>
                        <textarea class="form-control" id="currentVehicleLocation_{{$vehicle->id}}" name="currentVehicleLocation" rows="2" style="font-size: 14px;"></textarea>
                    </div>

                    <!-- Vehicle Available Location (Only for "Completed") -->
                    <div class="mb-3" id="vehicleAvailableLocationSection_{{$vehicle->id}}" style="display: none;">
                        <label for="vehicleAvailableLocation_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Vehicle Available Location:</label>
                        <select class="form-control" id="vehicleAvailableLocation_{{$vehicle->id}}" name="vehicleAvailableLocation" style="font-size: 14px;">
                            <option value="" disabled selected>Select a location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="Comment_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Add Comment:</label>
                        <textarea class="form-control" id="Comment_{{$vehicle->id}}" name="docComment" rows="3" style="font-size: 14px;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitVehModiStatus('{{ $vehicle->id ?? '' }}', '{{ $workOrder->wo_number ?? '' }}', '{{ $vehicle->vin ?? '' }}')">Update Status</button>						
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
(function() {
    // Attach the submitVehModiStatus function to the global window object
    window.submitVehModiStatus = function(woVehicleId, woNumber, vin) {
        // Get the selected status
        const selectedStatusElement = document.querySelector(`#updatevehModiStatusModal_${woVehicleId} input[name="vehModiStatus_${woVehicleId}"]:checked`);

        // Check if a status has been selected
        if (!selectedStatusElement) {
            alertify.error('Please select a status before submitting.');
            return;
        }

        const selectedStatus = selectedStatusElement.value;

        // If the selected status is "Initiated", validate the expected completion date and time
        if (selectedStatus === 'Initiated') {
            const expectedCompletion = document.getElementById(`expectedCompletion_${woVehicleId}`).value;
            
            if (expectedCompletion) {
                const selectedDateTime = new Date(expectedCompletion);
                const currentDateTime = new Date();

                // Check if the selected date and time is in the past
                if (selectedDateTime < currentDateTime) {
                    alertify.error('Expected completion date and time must be greater than or equal to the current date and time.');
                    return;
                }
            } else {
                alertify.error('Please enter the expected completion date and time.');
                return;
            }
        }

        // Display the confirmation dialog
        alertify.confirm(
            'Confirmation Required',
            `Are you sure you want to update the status of vehicle ${vin} in work order ${woNumber} to ${selectedStatus}?"`,
            function() { // If the user clicks "OK"
                const comment = document.getElementById(`Comment_${woVehicleId}`).value;
                const expectedCompletion = document.getElementById(`expectedCompletion_${woVehicleId}`).value;
                const currentVehicleLocation = document.getElementById(`currentVehicleLocation_${woVehicleId}`).value;
                const vehicleAvailableLocation = document.getElementById(`vehicleAvailableLocation_${woVehicleId}`).value;

                // Perform the AJAX request to update the status
                $.ajax({
                    url: '/update-vehicle-modification-status',
                    method: 'POST',
                    data: {
                        woVehicleId: woVehicleId,
                        status: selectedStatus,
                        comment: comment,
                        expected_completion_datetime: expectedCompletion,
                        current_vehicle_location: currentVehicleLocation,
                        vehicle_available_location: vehicleAvailableLocation,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alertify.success(response.message);
                        $(`#updatevehModiStatusModal_${woVehicleId}`).modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        alertify.error('Failed to update status');
                    }
                });
            },
            function() { // If the user clicks "Cancel"
                alertify.error('Action canceled');
            }
        );
    };

    // Show/hide fields based on the selected status
    document.querySelectorAll(`input[name="vehModiStatus_{{$vehicle->id}}"]`).forEach(function(input) {
        input.addEventListener('change', function() {
            const selectedStatus = this.value;
            const expectedCompletionSection = document.getElementById(`expectedCompletionSection_{{$vehicle->id}}`);
            const currentVehicleLocationSection = document.getElementById(`currentVehicleLocationSection_{{$vehicle->id}}`);
            const vehicleAvailableLocationSection = document.getElementById(`vehicleAvailableLocationSection_{{$vehicle->id}}`);

            // Show fields based on the selected status
            if (selectedStatus === 'Initiated') {
                expectedCompletionSection.style.display = 'block';
                currentVehicleLocationSection.style.display = 'block';
                vehicleAvailableLocationSection.style.display = 'none';
            } else if (selectedStatus === 'Completed') {
                expectedCompletionSection.style.display = 'none';
                currentVehicleLocationSection.style.display = 'none';
                vehicleAvailableLocationSection.style.display = 'block';
            } else {
                // Hide all conditional sections for other statuses
                expectedCompletionSection.style.display = 'none';
                currentVehicleLocationSection.style.display = 'none';
                vehicleAvailableLocationSection.style.display = 'none';
            }
        });
    });

    // Check for a pre-selected radio button and trigger the change event
    const initialSelectedRadio = document.querySelector(`input[name="vehModiStatus_{{$vehicle->id}}"]:checked`);
    if (initialSelectedRadio) {
        initialSelectedRadio.dispatchEvent(new Event('change'));
    }
})();

</script>


