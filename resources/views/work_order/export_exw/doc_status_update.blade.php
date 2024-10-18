@if(isset($data))
<div class="modal fade" id="updateDocStatusModal_{{$data->id}}" tabindex="-1" aria-labelledby="updateDocStatusModalLabel_{{$data->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Add modal-dialog here -->
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

                    <!-- BOE Fields -->
                    <div id="boeFields_{{$data->id}}" style="display: none;">
                        @php
                            $boeCount = $data->total_number_of_boe == 0 ? 1 : $data->total_number_of_boe;
                            $woNumber = $data->wo_number;
                            $boeData = $data->boe ?? [];
                        @endphp
                        @for($i = 0; $i < $boeCount; $i++)
                            @php
                                $boeNumber = sprintf("%s-BOE%02d", $woNumber, $i + 1);
                                $boeInfo = $boeData[$i] ?? null;
                            @endphp
                            <div class="boe-set mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="boeNumber_{{$data->id}}_{{ $i }}" class="form-label">BOE Number:</label>
                                        <input type="text" class="form-control" id="boeNumber_{{$data->id}}_{{ $i }}" name="boeNumber[]" value="{{ $boeInfo->boe ?? $boeNumber }}" readonly>
                                        <input type="text" class="form-control" id="boe_{{$data->id}}_{{ $i }}" name="boe[]" value="{{ $boeInfo->boe_number ?? ($i+1) }}" hidden>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="declarationNumber_{{$data->id}}_{{ $i }}" class="form-label">Declaration Number:</label>
                                        <input type="text" class="form-control" id="declarationNumber_{{$data->id}}_{{ $i }}" name="declarationNumber[]" maxlength="13" pattern="\d*" placeholder="Enter 13 digit Declaration Number" value="{{ $boeInfo->declaration_number ?? '' }}">
                                        <span id="declarationNumberError_{{$data->id}}_{{ $i }}" class="text-danger"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="declarationDate_{{$data->id}}_{{ $i }}" class="form-label">Declaration Date:</label>
                                        <input type="date" class="form-control" id="declarationDate_{{$data->id}}_{{ $i }}" name="declarationDate[]" value="{{ $boeInfo->declaration_date ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        @endfor
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
@if($data->sales_support_data_confirmation_at != '' && $data->finance_approval_status == 'Approved' && $data->coo_approval_status == 'Approved')
<script type="text/javascript">
    function toggleFields_{{$data->id}}() {
        // Try to get the selected radio button
        const selectedStatusInput = document.querySelector('input[name="docStatus_{{$data->id}}"]:checked');

        // Check if the selected radio input exists
        if (selectedStatusInput) {
            const selectedStatus = selectedStatusInput.value;
            const boeFields = document.getElementById('boeFields_{{$data->id}}');

            if (selectedStatus === 'Ready') {
                boeFields.style.display = 'block';
            } else {
                boeFields.style.display = 'none';
            }
        } else {
            console.error('No radio input selected for "docStatus_{{$data->id}}".');
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        toggleFields_{{$data->id}}(); // Ensure fields are toggled correctly on page load
    });
    document.querySelectorAll('input[name="docStatus_{{$data->id}}"]').forEach((radio) => {
        radio.addEventListener('change', toggleFields_{{$data->id}});
    });

    function submiDtocStatus(workOrderId, woNumber) {
        document.querySelectorAll('.text-danger').forEach(function(span) {
            span.textContent = ''; // Clear all error messages
        });
        const selectedStatus = document.querySelector('#updateDocStatusModal_' + workOrderId + ' input[name="docStatus_' + workOrderId + '"]:checked');
        if (!selectedStatus) {
            alertify.error('Please select a documentation status.');
            return; // Exit if no status is selected
        }
        let valid = true; // Assume form is valid
        if (selectedStatus.value === 'Ready') {
            document.querySelectorAll('.boe-set').forEach((boeSet, index) => {
                const declarationNumberField = document.getElementById('declarationNumber_'+workOrderId+'_' + index);
                // console.log()
                if (declarationNumberField) {
                    const declarationNumber = declarationNumberField.value; 
                    if (declarationNumber && (!/^\d{13}$/.test(declarationNumber) || parseInt(declarationNumber) <= 0)) {
                        document.getElementById('declarationNumberError_'+workOrderId+'_' + index).textContent = 'Please enter a valid 13-digit positive Declaration Number.';
                        valid = false;
                    }
                }
            });
        }
        if (!valid) {
            return; // Stop submission if validation fails
        }
        const boeData = [];
        document.querySelectorAll('.boe-set').forEach((boeSet, index) => {
            const boeNumberField = document.getElementById(`boeNumber_${workOrderId}_${index}`);
            const boeField = document.getElementById(`boe_${workOrderId}_${index}`);
            const declarationNumberField = document.getElementById(`declarationNumber_${workOrderId}_${index}`);
            const declarationDateField = document.getElementById(`declarationDate_${workOrderId}_${index}`);
            
            if (boeNumberField && declarationNumberField && declarationDateField) {
                boeData.push({                    
                    boe_number: boeField.value,  // BOE Number
                    boe: boeNumberField.value,  // BOE Number
                    declaration_number: declarationNumberField.value,  // Declaration Number (if provided)
                    declaration_date: declarationDateField.value  // Declaration Date
                });
            }
        });

        const comment = document.getElementById('docComment_' + workOrderId).value;

        // Display confirmation dialog
        alertify.confirm(
            'Confirmation Required',
            `Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus.value}?`,
            function() { // If the user clicks "OK"
                $.ajax({
                    url: '/update-wo-doc-status',
                    method: 'POST',
                    data: {
                        workOrderId: workOrderId,
                        status: selectedStatus.value,
                        comment: comment,
                        boeData: boeData,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alertify.success(response.message);
                        $('#updateDocStatusModal_' + workOrderId).modal('hide');
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
@endif
