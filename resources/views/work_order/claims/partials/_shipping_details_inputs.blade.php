{{--
    Per-VIN shipping details entered alongside the claim.
    Expects: $data (WOBOE, with workOrder.vehicles and shippingDetails loaded)

    Claim side only - these values are independent of the work order's own
    container_number / final_destination fields.
--}}
@php
    $shippingVehicles = optional($data->workOrder)->vehicles ?? collect();
    $savedShipping = $data->shippingDetails ?? collect();
    // One container / BL / destination usually covers every VIN, so each filled
    // cell offers a copy-down button. Pointless when there is only one row.
    $allowCopyDown = $shippingVehicles->count() > 1;
@endphp
<hr>
<div class="row js-shipping-section">
    <div class="col-12">
        <h6 class="mb-2">Shipping Details (Per VIN)</h6>
        @if($shippingVehicles->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th style="min-width:180px;">VIN</th>
                            <th style="min-width:190px;">Container Number</th>
                            <th style="min-width:190px;">BL Number</th>
                            <th style="min-width:230px;">Final Destination</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shippingVehicles as $shipVeh)
                            @php
                                $detail = $savedShipping->firstWhere('w_o_vehicle_id', $shipVeh->id);
                            @endphp
                            <tr>
                                <td>
                                    {{ $shipVeh->vin ?? '' }}
                                </td>
                                <td>
                                    <div class="js-copy-cell d-flex align-items-center">
                                        <input type="text"
                                            class="form-control form-control-sm js-copy-source js-shipping-container"
                                            name="shipping_details[{{ $data->id }}][{{ $shipVeh->id }}][container_number]"
                                            maxlength="100" placeholder="Container Number"
                                            value="{{ $detail->container_number ?? '' }}">
                                        @if($allowCopyDown)
                                            <button type="button" class="btn btn-sm btn-outline-info js-copy-down"
                                                data-column="container" style="display:none;"
                                                title="Copy this Container Number to all other VINs">
                                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="js-copy-cell d-flex align-items-center">
                                        <input type="text"
                                            class="form-control form-control-sm js-copy-source js-shipping-bl"
                                            name="shipping_details[{{ $data->id }}][{{ $shipVeh->id }}][bl_number]"
                                            maxlength="100" placeholder="BL Number"
                                            value="{{ $detail->bl_number ?? '' }}">
                                        @if($allowCopyDown)
                                            <button type="button" class="btn btn-sm btn-outline-info js-copy-down"
                                                data-column="bl" style="display:none;"
                                                title="Copy this BL Number to all other VINs">
                                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="js-copy-cell d-flex align-items-center">
                                        <div class="js-copy-select-wrap flex-grow-1">
                                            <select class="form-control form-control-sm js-copy-source js-claim-country js-shipping-country"
                                                name="shipping_details[{{ $data->id }}][{{ $shipVeh->id }}][final_destination_country_id]"
                                                data-selected="{{ $detail->final_destination_country_id ?? '' }}">
                                                {{-- options injected on modal open --}}
                                            </select>
                                        </div>
                                        @if($allowCopyDown)
                                            <button type="button" class="btn btn-sm btn-outline-info js-copy-down"
                                                data-column="country" style="display:none;"
                                                title="Copy this Final Destination to all other VINs">
                                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <span id="shippingDetailsError_{{ $data->id }}" class="text-danger"></span>
        @else
            <p class="text-muted mb-0">No vehicles found against this work order.</p>
        @endif
    </div>
</div>
