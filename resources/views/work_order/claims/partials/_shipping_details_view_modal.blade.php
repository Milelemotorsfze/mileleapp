{{--
    Read-only view of the per-VIN shipping details saved against a claim.
    Expects: $data (WOBOE, with workOrder.vehicles and
             shippingDetails.finalDestinationCountry loaded)
--}}
@php
    $viewVehicles = optional($data->workOrder)->vehicles ?? collect();
    $viewShipping = $data->shippingDetails ?? collect();
    $hasShipping = $viewShipping->count() > 0;
@endphp
<div class="modal fade" id="shippingDetailsModal_{{ $data->id }}" tabindex="-1"
    aria-labelledby="shippingDetailsModalLabel_{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shippingDetailsModalLabel_{{ $data->id }}">
                    Shipping Details For {{ $data->boe ?? '' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($hasShipping)
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0">
                            <thead style="background-color: #e6f1ff">
                                <tr>
                                    <th>VIN</th>
                                    <th>Container Number</th>
                                    <th>BL Number</th>
                                    <th>Final Destination</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($viewVehicles as $viewVeh)
                                    @php
                                        $viewDetail = $viewShipping->firstWhere('w_o_vehicle_id', $viewVeh->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $viewVeh->vin ?? '' }}</td>
                                        <td>{{ $viewDetail->container_number ?? 'NA' }}</td>
                                        <td>{{ $viewDetail->bl_number ?? 'NA' }}</td>
                                        <td>{{ optional($viewDetail)->finalDestinationCountry->name ?? 'NA' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No shipping details have been saved for this BOE.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
