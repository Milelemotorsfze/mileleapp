@if(isset($workOrder))
    @php
        $boeCount = $workOrder->total_number_of_boe == 0 ? 1 : $workOrder->total_number_of_boe;
        $woNumber = $workOrder->wo_number; 
        $boeData = $workOrder->boe ?? []; 
    @endphp

    @if($workOrder->sales_support_data_confirmation_at != '' && $workOrder->finance_approval_status == 'Approved' && $workOrder->coo_approval_status == 'Approved')
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-change-documentation-status']);
        @endphp
        @if ($hasPermission)
			<a class="me-2 btn btn-sm btn-info" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateDocStatusModal">
            <i class="fa fa-file" aria-hidden="true"></i> Update Doc Status
            </a>
            <div class="modal fade" id="updateDocStatusModal" tabindex="-1" aria-labelledby="updateDocStatusModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
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

                                <div class="mb-3 mt-3">
                                    <label for="docComment" class="form-label">Add Comment:</label>
                                    <textarea class="form-control" id="docComment" name="docComment" rows="3"></textarea>
                                    <span id="docCommentError" class="text-danger"></span>
                                </div>
                                <div id="claimCheckboxField" class="form-check mt-3" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="hasClaimCheckbox" name="hasClaim" value="1" 
                                        {{ $workOrder->has_claim == 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hasClaimCheckbox">Has Claim</label>
                                </div></br>
                                <div id="boeFields">
                                    @for($i = 0; $i < $boeCount; $i++)
                                        @php
                                            $boeNumber = sprintf("%s-BOE%02d", $woNumber, $i+1);
                                            $boeInfo = $boeData[$i] ?? null; 
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
    function toggleDeclarationFields() {
        const selectedStatusElement = document.querySelector('input[name="docStatus"]:checked');
        const declarationFields = document.getElementById('boeFields');
        const claimCheckboxField = document.getElementById('claimCheckboxField');
        if (!selectedStatusElement) {
            console.error('No radio button is selected or present.');
            return; 
        }
        
        const selectedStatus = selectedStatusElement.value;

        if (selectedStatus === 'Ready') {
            declarationFields.style.display = 'block';
            claimCheckboxField.style.display = 'block'; // Show the Has Claim checkbox
        } else {
            declarationFields.style.display = 'none';
            claimCheckboxField.style.display = 'none'; // Hide the Has Claim checkbox
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        $('#updateDocStatusModal').on('shown.bs.modal', function () {
            toggleDeclarationFields(); 
        });
    });

    document.querySelectorAll('input[name="docStatus"]').forEach((radio) => {
        radio.addEventListener('change', toggleDeclarationFields);
    });

    function submitDocStatus(workOrderId, woNumber) {
        document.querySelectorAll('.text-danger').forEach(function(span) {
            span.textContent = ''; 
        });

        const selectedStatusElement = document.querySelector('input[name="docStatus"]:checked');
        if (!selectedStatusElement) {
            alertify.error('Please select a documentation status.');
            return; 
        }

        const selectedStatus = selectedStatusElement.value;
        const boeFields = document.querySelectorAll('.boe-set');

        const boeData = [];
        let valid = true;
        boeFields.forEach((boeSet, index) => {
            const declarationNumber = document.getElementById(`declarationNumber_${index}`).value;
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
            return; 
        }

        const comment = document.getElementById('docComment').value;
        const hasClaim = document.getElementById('hasClaimCheckbox')?.checked || false; // Capture checkbox value

        alertify.confirm(
            'Confirmation Required',
            `Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus}?`,
            function() { 
                $.ajax({
                    url: '/update-wo-doc-status',
                    method: 'POST',
                    data: {
                        workOrderId: workOrderId,
                        status: selectedStatus,
                        comment: comment,
                        hasClaim: hasClaim, // Include Has Claim checkbox state
                        boeData: boeData,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        alertify.success(response.message);
                        $('#updateDocStatusModal').modal('hide');
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
@endif



