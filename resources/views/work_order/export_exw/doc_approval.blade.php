@if(isset($workOrder))
    @php
        // If total_number_of_boe is 0, consider it as 1
        $boeCount = $workOrder->total_number_of_boe == 0 ? 1 : $workOrder->total_number_of_boe;
        $woNumber = $workOrder->wo_number; // Capture the work order number
        $boeData = $workOrder->boe ?? []; // Get BOE data if available
    @endphp

    @if($workOrder->sales_support_data_confirmation_at != '' && $workOrder->finance_approval_status == 'Approved' && $workOrder->coo_approval_status == 'Approved')
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-change-documentation-status']);
        @endphp
        @if ($hasPermission)
			<a class="me-2 btn btn-sm btn-info" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateDocStatusModal">
            <i class="fa fa-file" aria-hidden="true"></i> Update Doc Status
            </a>
            <!-- Modal -->
            <div class="modal fade" id="updateDocStatusModal" tabindex="-1" aria-labelledby="updateDocStatusModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateDocStatusModalLabel">Update Documentation Status for {{$workOrder->wo_number ?? ''}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="docStatusForm">
                                <!-- Radio buttons for doc status -->
                                <div class="d-flex justify-content-between">
                                    <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                        <input class="form-check-input me-1" type="radio" name="docStatus" id="docStatusNotInitiated" value="Not Initiated" {{ $workOrder->docs_status == 'Not Initiated' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="docStatusNotInitiated">Not Initiated</label>
                                    </div>

                                    <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                        <input class="form-check-input me-1" type="radio" name="docStatus" id="docStatusInProgress" value="In Progress" {{ $workOrder->docs_status == 'In Progress' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="docStatusInProgress">In Progress</label>
                                    </div>

                                    <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                        <input class="form-check-input me-1" type="radio" name="docStatus" id="docStatusReady" value="Ready" {{ $workOrder->docs_status == 'Ready' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="docStatusReady">Ready</label>
                                    </div>
                                </div>

                                <!-- Comment section -->
                                <div class="mb-3 mt-3">
                                    <label for="docComment" class="form-label">Add Comment:</label>
                                    <textarea class="form-control" id="docComment" name="docComment" rows="3"></textarea>
                                    <span id="docCommentError" class="text-danger"></span>
                                </div>

                                <!-- Dynamic BOE and Declaration Fields -->
                                <div id="boeFields">
                                    @for($i = 0; $i < $boeCount; $i++)
                                        @php
                                            // Handle both the case where BOE data exists and where it doesn't
                                            $boeNumber = sprintf("%s-BOE%02d", $woNumber, $i+1);
                                            $boeInfo = $boeData[$i] ?? null; // Use BOE data if available
                                        @endphp
                                        <div class="boe-set mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="boeNumber_{{ $i }}" class="form-label">BOE Number:</label>
                                                    <input type="text" class="form-control" id="boeNumber_{{ $i }}" name="boeNumber[]" value="{{ $boeInfo->boe ?? $boeNumber }}" readonly>
                                                    <input type="text" class="form-control" id="boe_{{ $i }}" name="boe[]" value="{{ $boeInfo->boe_number ?? ($i+1) }}" hidden>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="declarationNumber_{{ $i }}" class="form-label">Declaration Number:</label>
                                                    <input type="text" class="form-control declaration-number" id="declarationNumber_{{ $i }}" name="declarationNumber[]" maxlength="13" pattern="\d*" placeholder="Enter 13 digit Declaration Number" value="{{ $boeInfo->declaration_number ?? '' }}">
                                                    <span id="declarationNumberError_{{ $i }}" class="text-danger"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="declarationDate_{{ $i }}" class="form-label">Declaration Date:</label>
                                                    <input type="date" class="form-control" id="declarationDate_{{ $i }}" name="declarationDate[]" value="{{ $boeInfo->declaration_date ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" onclick="submitDocStatus('{{ $workOrder->id }}', '{{ $workOrder->wo_number }}')">Update Status</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif

@if(isset($workOrder))
<script type="text/javascript">
    // Function to toggle the display of Declaration Fields based on the selected status
    function toggleDeclarationFields() {
        // Check if any radio button is selected
        const selectedStatusElement = document.querySelector('input[name="docStatus"]:checked');
        
        // Handle case where no radio button is selected
        if (!selectedStatusElement) {
            console.error('No radio button is selected or present.');
            return; // Exit the function if no radio button is selected
        }
        
        const selectedStatus = selectedStatusElement.value;
        const declarationFields = document.getElementById('boeFields');

        // Show BOE and Declaration fields only if the status is "Ready"
        if (selectedStatus === 'Ready') {
            declarationFields.style.display = 'block';
        } else {
            declarationFields.style.display = 'none';
        }
    }

    // Ensure that the fields are shown/hidden correctly when the modal is opened
    document.addEventListener('DOMContentLoaded', function() {
        $('#updateDocStatusModal').on('shown.bs.modal', function () {
            toggleDeclarationFields(); // Trigger field visibility check based on pre-selected status when modal is shown
        });
    });

    // Event listener for radio button changes
    document.querySelectorAll('input[name="docStatus"]').forEach((radio) => {
        radio.addEventListener('change', toggleDeclarationFields);
    });

    function submitDocStatus(workOrderId, woNumber) {
        // Clear previous errors
        document.querySelectorAll('.text-danger').forEach(function(span) {
            span.textContent = ''; // Clear all error messages
        });

        // Check for radio button selection before proceeding
        const selectedStatusElement = document.querySelector('input[name="docStatus"]:checked');
        if (!selectedStatusElement) {
            alertify.error('Please select a documentation status.');
            return; // Prevent form submission if no status is selected
        }

        const selectedStatus = selectedStatusElement.value;
        const boeFields = document.querySelectorAll('.boe-set');

        const boeData = [];
        let valid = true;
        boeFields.forEach((boeSet, index) => {
            const declarationNumber = document.getElementById(`declarationNumber_${index}`).value;
            // If Declaration Number is provided, validate it: it must be 13 digits and positive
            if (declarationNumber && (!/^\d{13}$/.test(declarationNumber) || parseInt(declarationNumber) <= 0)) {
                document.getElementById(`declarationNumberError_${index}`).textContent = 'Please enter a valid 13-digit positive Declaration Number.';
                valid = false;
            }

            boeData.push({
                boe_number: document.getElementById(`boe_${index}`).value,
                boe: document.getElementById(`boeNumber_${index}`).value,
                declaration_number: declarationNumber,
                declaration_date: document.getElementById(`declarationDate_${index}`).value
            });
        });
        if (!valid) {
            return; // Stop form submission if there are validation errors
        }

        const comment = document.getElementById('docComment').value;

        // Display confirmation dialog
        alertify.confirm(
            'Confirmation Required',
            `Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus}?`,
            function() { // If the user clicks "OK"
                // Perform the AJAX request to update the status
                $.ajax({
                    url: '/update-wo-doc-status',
                    method: 'POST',
                    data: {
                        workOrderId: workOrderId,
                        status: selectedStatus,
                        comment: comment,
                        boeData: boeData,
                        _token: '{{ csrf_token() }}' // Laravel CSRF token
                    },
                    success: function(response) {
                        alertify.success(response.message);
                        $('#updateDocStatusModal').modal('hide');
                        location.reload(); // Reload the page after success
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
    }
</script>
@endif



