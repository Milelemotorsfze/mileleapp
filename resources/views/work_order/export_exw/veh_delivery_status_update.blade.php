<div class="modal fade" id="updatevehDeliveryStatusModal_{{$vehicle->id}}" tabindex="-1" aria-labelledby="updateStatusModalLabel_{{$vehicle->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Added 'modal-lg' for larger modal width -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel_{{$vehicle->id}}">Update Vehicle Delivery Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="vehDeliveryStatusForm_{{$vehicle->id}}">               
                    <div class="mb-3 mt-3 d-flex justify-content-between">
                        <p>Work Order: <strong>{{$workOrder->wo_number ?? ''}}</strong></p>
                        <p>VIN: <strong>{{$vehicle->vin ?? ''}}</strong></p>
                    </div>

                    <!-- Status radio buttons -->
                    <div class="d-flex justify-content-between flex-wrap"> <!-- Added 'flex-wrap' to avoid crowding -->
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehDeliveryStatus_{{$vehicle->id}}" id="vehDeliveryStatusOnHold_{{$vehicle->id}}" value="On Hold" {{ $vehicle->Delivery_status == 'On Hold' ? 'checked' : '' }}>
                            <label class="form-check-label" for="DeliveryOnHold_{{$vehicle->id}}" style="font-size: 14px;">On Hold</label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehDeliveryStatus_{{$vehicle->id}}" id="vehDeliveryStatusReady_{{$vehicle->id}}" value="Ready" {{ $vehicle->Delivery_status == 'Ready' ? 'checked' : '' }}>
                            <label class="form-check-label" for="DeliveryReady_{{$vehicle->id}}" style="font-size: 14px;">Ready</label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehDeliveryStatus_{{$vehicle->id}}" id="vehDeliveryStatusDelivered_{{$vehicle->id}}" value="Delivered" {{ $vehicle->Delivery_status == 'Delivered' ? 'checked' : '' }}>
                            <label class="form-check-label" for="DeliveryDelivered_{{$vehicle->id}}" style="font-size: 14px;">Delivered With Documents</label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehDeliveryStatus_{{$vehicle->id}}" id="vehDeliveryStatusDocsHold_{{$vehicle->id}}" value="Delivered With Docs Hold" {{ $vehicle->Delivery_status == 'Delivered With Docs Hold' ? 'checked' : '' }}>
                            <label class="form-check-label" for="DeliveryDocsHold_{{$vehicle->id}}" style="font-size: 14px;">Delivered/Documents Hold</label>
                        </div>
                    </div>

                    <!-- Ready: Delivery at (DateTime) and Location Dropdown -->
                    <div class="mb-3 mt-3" id="readyFields_{{$vehicle->id}}" style="display:none;">
                        <span class="error">* </span>
                        <label for="deliveryAt_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Delivery At:</label>
                        <input type="datetime-local" class="form-control" id="deliveryAt_{{$vehicle->id}}" name="delivery_at" min="{{ now()->format('Y-m-d\TH:i') }}" style="font-size: 14px;">
                        <span class="error">* </span>
                        <label for="location_{{$vehicle->id}}" class="form-label mt-3" style="font-size: 14px;">Location:</label>
                        <select class="form-select" id="location_{{$vehicle->id}}" name="location" style="font-size: 14px;">
                            <option value=""></option>
                            @foreach($locations as $location)
                            <option value="{{$location->id ?? ''}}">{{$location->name ?? ''}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Delivered: GDN Number and Delivered At (DateTime) -->
                    <div class="mb-3 mt-3" id="deliveredFields_{{$vehicle->id}}" style="display:none;">
                        <label for="gdnNumber_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">GDN Number:</label>
                        <input type="text" class="form-control" id="gdnNumber_{{$vehicle->id}}" name="gdn_number" style="font-size: 14px;">

                        <label for="deliveredAt_{{$vehicle->id}}" class="form-label mt-3" style="font-size: 14px;">Delivered At:</label>

                        <input type="date" class="form-control" id="deliveredAt_{{$vehicle->id}}" name="delivered_at" style="font-size: 14px;">
                    </div>

                    <!-- Delivered With Docs Hold: Docs Delivery Date -->
                    <div class="mb-3 mt-3" id="docsHoldFields_{{$vehicle->id}}" style="display:none;">
                        <label for="docsDeliveryDate_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Docs Delivery Date:</label>
                        <input type="date" class="form-control" id="docsDeliveryDate_{{$vehicle->id}}" name="doc_delivery_date" style="font-size: 14px;">
                    </div>

                    <!-- Comment Field -->
                    <div class="mb-3 mt-3">
                        <label for="DeliveryComment_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Remarks:</label>
                        <textarea class="form-control" id="DeliveryComment_{{$vehicle->id}}" name="comment" rows="3" style="font-size: 14px;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitVehDeliveryStatus('{{ $vehicle->id ?? '' }}', '{{ $workOrder->wo_number ?? '' }}', '{{ $vehicle->vin ?? '' }}')">Update Status</button>						
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function() {
        // Function to get the current date and time in the correct format for datetime-local input
        function getCurrentDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Function to submit the Delivery status
        window.submitVehDeliveryStatus = function(woVehicleId, woNumber, vin) {
            const selectedStatusElement = document.querySelector(`#updatevehDeliveryStatusModal_${woVehicleId} input[name="vehDeliveryStatus_${woVehicleId}"]:checked`);
            
            if (!selectedStatusElement) {
                alertify.error('Please select a status before submitting.');
                return;
            }

            const selectedStatus = selectedStatusElement.value;
            const deliveryAt = document.getElementById(`deliveryAt_${woVehicleId}`)?.value || '';
            const gdnNumber = document.getElementById(`gdnNumber_${woVehicleId}`)?.value || '';
            const docsDeliveryDate = document.getElementById(`docsDeliveryDate_${woVehicleId}`)?.value || '';
            const location = document.getElementById(`location_${woVehicleId}`)?.value || '';
            const comment = document.getElementById(`DeliveryComment_${woVehicleId}`).value;
            const deliveredAt = document.getElementById(`deliveredAt_${woVehicleId}`)?.value || ''; // Capture the delivered_at field

            // Validate based on the status
            if (selectedStatus === 'Ready' && (!deliveryAt || !location)) {
                alertify.error('Please provide a valid delivery time and location.');
                return;
            }
            if (selectedStatus === 'Delivered' && !deliveredAt) {
                alertify.error('Please provide a valid delivery time for Delivered status.');
                return;
            }

            // Display the confirmation dialog
            alertify.confirm(
                'Confirmation Required',
                `Are you sure you want to update the delivery status of vehicle ${vin} in work order ${woNumber} to ${selectedStatus}?`,
                function() {
                    // Perform the AJAX request to update the status
                    $.ajax({
                        url: '/update-vehicle-delivery-status',
                        method: 'POST',
                        data: {
                            woVehicleId: woVehicleId,
                            status: selectedStatus,
                            delivery_at: selectedStatus === 'Ready' ? deliveryAt : null,
                            delivered_at: selectedStatus === 'Delivered' ? deliveredAt : getCurrentDateTime(), // Set default current time if not provided
                            location: selectedStatus === 'Ready' ? location : null,
                            gdn_number: selectedStatus === 'Delivered' ? gdnNumber : null,
                            doc_delivery_date: selectedStatus === 'Delivered With Docs Hold' ? docsDeliveryDate : null,
                            comment: comment,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alertify.success(response.message);
                            $(`#updatevehDeliveryStatusModal_${woVehicleId}`).modal('hide');
                            // Reload the page after the modal is hidden
                            window.setTimeout(function() {
                                window.location.reload(); // Reload the page after success
                            }, 1000); // Optionally add a delay to show the success message before reloading
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
        };

        // Show/hide fields based on the selected status
        function toggleFieldsBasedOnStatus(woVehicleId) {
            const selectedStatus = document.querySelector(`input[name="vehDeliveryStatus_${woVehicleId}"]:checked`)?.value;
            const readyFields = document.getElementById(`readyFields_${woVehicleId}`);
            const deliveredFields = document.getElementById(`deliveredFields_${woVehicleId}`);
            const docsHoldFields = document.getElementById(`docsHoldFields_${woVehicleId}`);
            const deliveredAtInput = document.getElementById(`deliveredAt_${woVehicleId}`);

            // Hide all fields initially
            readyFields.style.display = 'none';
            deliveredFields.style.display = 'none';
            docsHoldFields.style.display = 'none';

            // Show the appropriate fields based on the selected status
            if (selectedStatus === 'Ready') {
                readyFields.style.display = 'block';
            } else if (selectedStatus === 'Delivered') {
                deliveredFields.style.display = 'block';
                // Set the default value of the deliveredAt field to the current datetime
                deliveredAtInput.value = getCurrentDateTime();
            } else if (selectedStatus === 'Delivered With Docs Hold') {
                docsHoldFields.style.display = 'block';
            }
        }

        // Attach event listener to radio buttons
        document.querySelectorAll(`input[name="vehDeliveryStatus_{{$vehicle->id}}"]`).forEach(function(input) {
            input.addEventListener('change', function() {
                toggleFieldsBasedOnStatus('{{$vehicle->id}}');
            });
        });

        // Trigger visibility check when the modal is shown
        $(`#updatevehDeliveryStatusModal_{{$vehicle->id}}`).on('shown.bs.modal', function () {
            toggleFieldsBasedOnStatus('{{$vehicle->id}}');
        });

        // Check for a pre-selected radio button and trigger the change event when the page loads
        const initialSelectedRadio = document.querySelector(`input[name="vehDeliveryStatus_{{$vehicle->id}}"]:checked`);
        if (initialSelectedRadio) {
            toggleFieldsBasedOnStatus('{{$vehicle->id}}');
        }
    })();
</script>