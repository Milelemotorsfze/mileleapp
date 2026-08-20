{{--
    Per-VIN shipping details entered alongside the claim.
    Expects: $data (WOBOE, with workOrder.vehicles and shippingDetails loaded)

    Claim side only - these values are independent of the work order's own
    container_number / final_destination fields.
--}}
@php
    $shippingVehicles = optional($data->workOrder)->vehicles ?? collect();
    $savedShipping = $data->shippingDetails ?? collect();
@endphp
<hr>
<div class="row">
    <div class="col-12">
        <h6 class="mb-2">Shipping Details (Per VIN)</h6>
        @if($shippingVehicles->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th style="min-width:180px;">VIN</th>
                            <th style="min-width:160px;">Container Number</th>
                            <th style="min-width:160px;">BL Number</th>
                            <th style="min-width:200px;">Final Destination</th>
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
                                    <input type="text" class="form-control form-control-sm js-shipping-container"
                                        name="shipping_details[{{ $data->id }}][{{ $shipVeh->id }}][container_number]"
                                        maxlength="100" placeholder="Container Number"
                                        value="{{ $detail->container_number ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm js-shipping-bl"
                                        name="shipping_details[{{ $data->id }}][{{ $shipVeh->id }}][bl_number]"
                                        maxlength="100" placeholder="BL Number"
                                        value="{{ $detail->bl_number ?? '' }}">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm js-claim-country"
                                        name="shipping_details[{{ $data->id }}][{{ $shipVeh->id }}][final_destination_country_id]"
                                        data-selected="{{ $detail->final_destination_country_id ?? '' }}">
                                        {{-- options injected on modal open --}}
                                    </select>
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
