<div class="modal fade" id="updatevehPDIStatusModal_{{$vehicle->id}}" tabindex="-1" aria-labelledby="updateStatusModalLabel_{{$vehicle->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel_{{$vehicle->id}}">Update Vehicle PDI Status</h5></br>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="vehPdiStatusForm_{{$vehicle->id}}">               
                    <div class="mb-3 mt-3 d-flex justify-content-between">
                        <p>Work Order : <strong>{{$workOrder->wo_number ?? ''}}</strong></p>
                        <p>VIN : <strong>{{$vehicle->vin ?? ''}}</strong></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehPdiStatus_{{$vehicle->id}}" id="vehPdiStatusNotInitiated_{{$vehicle->id}}" value="Not Initiated" {{ $vehicle->pdi_status == 'Not Initiated' ? 'checked' : '' }}>
                            <label class="form-check-label" for="PdiNotInitiated_{{$vehicle->id}}" style="font-size: 14px;">Not Initiated</label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehPdiStatus_{{$vehicle->id}}" id="vehPdiStatusScheduled_{{$vehicle->id}}" value="Scheduled" {{ $vehicle->pdi_status == 'Scheduled' ? 'checked' : '' }}>
                            <label class="form-check-label" for="PdiScheduled_{{$vehicle->id}}" style="font-size: 14px;">Scheduled</label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="vehPdiStatus_{{$vehicle->id}}" id="vehPdiStatusCompleted_{{$vehicle->id}}" value="Completed" {{ $vehicle->pdi_status == 'Completed' ? 'checked' : '' }}>
                            <label class="form-check-label" for="PdiCompleted_{{$vehicle->id}}" style="font-size: 14px;">Completed</label>
                        </div>
                    </div>

                    {{-- PDI Scheduled Time Field --}}
                    <div class="mb-3 mt-3" id="pdiScheduledSection_{{$vehicle->id}}" style="display:none;">
                        <label for="pdiScheduledAt_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">PDI Scheduled Time:</label>
                        <input type="datetime-local" class="form-control" id="pdiScheduledAt_{{$vehicle->id}}" name="pdi_scheduled_at" min="{{ now()->format('Y-m-d\TH:i') }}" style="font-size: 14px;">
                    </div>

                    {{-- QC Inspection for Completed --}}
                    <div class="mb-3 mt-3" id="qcInspectionSection_{{$vehicle->id}}" style="display:none;">
                        <label class="form-label" style="font-size: 14px;">QC Inspection:</label>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="qc_inspection_{{$vehicle->id}}" id="qcPassed_{{$vehicle->id}}" value="Passed"
                                @if(optional($vehicle->latestPdiStatus)->passed_status == 'Passed') checked @endif>
                            <label class="form-check-label" for="qcPassed_{{$vehicle->id}}">Passed</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="qc_inspection_{{$vehicle->id}}" id="qcFailed_{{$vehicle->id}}" value="Failed"
                                @if(optional($vehicle->latestPdiStatus)->passed_status == 'Failed') checked @endif>
                            <label class="form-check-label" for="qcFailed_{{$vehicle->id}}">Failed</label>
                        </div>
                    </div>


                    {{-- QC Inspection Remarks for Failed --}}
                    <div class="mb-3 mt-3" id="qcRemarksSection_{{$vehicle->id}}" style="display:none;">
                        <label for="qcRemarks_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">QC Inspection Remarks:</label>
                        <textarea class="form-control" id="qcRemarks_{{$vehicle->id}}" name="qc_inspector_remarks" rows="3" style="font-size: 14px;"></textarea>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="pdiComment_{{$vehicle->id}}" class="form-label" style="font-size: 14px;">Add Comment:</label>
                        <textarea class="form-control" id="pdiComment_{{$vehicle->id}}" name="comment" rows="3" style="font-size: 14px;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitVehPdiStatus('{{ $vehicle->id ?? '' }}', '{{ $workOrder->wo_number ?? '' }}', '{{ $vehicle->vin ?? '' }}')">Update Status</button>						
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
(function() {
    window.submitVehPdiStatus = function(woVehicleId, woNumber, vin) {
        const selectedStatusElement = document.querySelector(`#updatevehPDIStatusModal_${woVehicleId} input[name="vehPdiStatus_${woVehicleId}"]:checked`);
        
        if (!selectedStatusElement) {
            alertify.error('Please select a status before submitting.');
            return;
        }

        const selectedStatus = selectedStatusElement.value;
        const pdiScheduledAt = document.getElementById(`pdiScheduledAt_${woVehicleId}`)?.value || '';
        const qcInspectionFailed = document.querySelector(`#qcFailed_${woVehicleId}`)?.checked || false;
        const qcInspectionRemarks = document.getElementById(`qcRemarks_${woVehicleId}`)?.value || '';

        if (selectedStatus === 'Scheduled' && !pdiScheduledAt) {
            alertify.error('Please provide a valid PDI scheduled time.');
            return;
        }

        if (selectedStatus === 'Completed' && qcInspectionFailed && !qcInspectionRemarks) {
            alertify.error('Please provide QC inspection remarks for a failed inspection.');
            return;
        }
        const passedStatus = selectedStatus === 'Completed' && qcInspectionFailed ? 'Failed' : (selectedStatus === 'Completed' ? 'Passed' : null);

        alertify.confirm(
            'Confirmation Required',
            `Are you sure you want to update the pdi status of vehicle ${vin} in work order ${woNumber} to ${selectedStatus}?`,
            function() {
                const comment = document.getElementById(`pdiComment_${woVehicleId}`).value;

                $.ajax({
                    url: '/update-vehicle-pdi-status',
                    method: 'POST',
                    data: {
                        woVehicleId: woVehicleId,
                        status: selectedStatus,
                        pdi_scheduled_at: selectedStatus === 'Scheduled' ? pdiScheduledAt : null,
                        passed_status: passedStatus,
                        qc_inspector_remarks: selectedStatus === 'Completed' && qcInspectionFailed ? qcInspectionRemarks : null,
                        comment: comment,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alertify.success(response.message);
                        $(`#updatevehPDIStatusModal_${woVehicleId}`).modal('hide');
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
    };

    document.querySelectorAll(`input[name="vehPdiStatus_{{$vehicle->id}}"]`).forEach(function(input) {
        input.addEventListener('change', function() {
            const selectedStatus = this.value;
            const pdiScheduledSection = document.getElementById(`pdiScheduledSection_{{$vehicle->id}}`);
            const qcInspectionSection = document.getElementById(`qcInspectionSection_{{$vehicle->id}}`);
            const qcRemarksSection = document.getElementById(`qcRemarksSection_{{$vehicle->id}}`);

            if (selectedStatus === 'Scheduled') {
                pdiScheduledSection.style.display = 'block';
            } else {
                pdiScheduledSection.style.display = 'none';
            }

            if (selectedStatus === 'Completed') {
                qcInspectionSection.style.display = 'block';

                document.querySelectorAll(`input[name="qc_inspection_{{$vehicle->id}}"]`).forEach(function(qcInput) {
                    qcInput.addEventListener('change', function() {
                        const qcStatus = this.value;
                        if (qcStatus === 'Failed') {
                            qcRemarksSection.style.display = 'block';
                        } else {
                            qcRemarksSection.style.display = 'none';
                        }
                    });

                    if (qcInput.checked && qcInput.value === 'Failed') {
                        qcRemarksSection.style.display = 'block';
                    } else {
                        qcRemarksSection.style.display = 'none';
                    }
                });
            } else {
                qcInspectionSection.style.display = 'none';
                qcRemarksSection.style.display = 'none';
            }
        });
    });

    const initialSelectedRadio = document.querySelector(`input[name="vehPdiStatus_{{$vehicle->id}}"]:checked`);
    if (initialSelectedRadio) {
        initialSelectedRadio.dispatchEvent(new Event('change'));
    }
})();
</script>
