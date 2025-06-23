@extends('layouts.main')

<style>
    .so-details-header {
        background: #4d9d30 !important;
    }
</style>

@section('content')
<div class="card-header">
    <h1 class="card-title">Resolve Duplicate Sales Orders for: <strong>{{ $displaySoNumber }}</strong></h1>


    {{-- Flash Messages --}}
    @if (Session::has('error'))
    <div class="alert alert-danger">
        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
        {{ Session::get('error') }}
    </div>
    @endif

    @if (Session::has('success'))
    <div class="alert alert-success" id="success-alert">
        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
        {{ Session::get('success') }}
    </div>
    @endif

    <div class="card-body">
        <form id="finalize-so-form" method="POST" action="{{ route('so_finalizations.store') }}">
            @csrf
            <div class="row">
                @foreach($soList as $so)
                <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-4">
                    <div class="card">
                        <div class="card-header so-details-header">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="finalized_so_id" id="so_{{ $so->id }}" value="{{ $so->id }}">
                                <label class="form-check-label" for="so_{{ $so->id }}">
                                    <h5 class="text-white mb-0">Select {{ $so->so_number }} (ID: {{ $so->id }})</h5>
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>SO Date:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->so_date }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Sales Person ID:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->salesperson->name ?? $so->sales_person_id }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Notes:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->notes }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Total:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->total }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Created By:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->createdByUser->name ?? $so->created_by }}</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Document Type:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotation->document_type ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Currency:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotation->currency ?? '' }}</p>
                                </div>
                            </div>

                            <hr>

                            <div class="col-12">
                                <h5>Client Details</h5>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Client Category:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>
                                        {{ $so->call && $so->call->company_name ? 'Company' : 'Individual' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Company:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->call->company_name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Customer:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->call->name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Contact Number:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->call->phone ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Email:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->call->email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Address:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->call->address ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            <div class="col-12">
                                <h5>Document Details</h5>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Document Validity:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->document_validity ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Sales Person:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotation->createdBy->name ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Sales Office:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->empProfile->office ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Sales Email:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotation->createdBy->email ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Sales Contact:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->empProfile->phone ?? '' }}</p>
                                </div>
                            </div>

                            <hr>

                            <div class="col-12">
                                <h5>Delivery Details</h5>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Final Destination:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->country->name ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Incoterm:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->incoterm ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Port of Discharge:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->shippingPort->name ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Port of Loading:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->shippingPortOfLoad->name ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            <div class="col-12">
                                <h5>Payment Details</h5>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Payment Terms:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->paymentterms->name ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Advance Amount:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->advance_amount ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            <div class="col-12">
                                <h5>Client Representative</h5>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Rep Name:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->representative_name ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <p><strong>Rep Number:</strong></p>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-6">
                                    <p>{{ $so->quotationDetail->representative_number ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            @if($so->vehicles->count())
                            <div class="col-12">
                                <h5 class="mt-3">Linked Vehicles</h5>
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>VIN</th>
                                            <th>Variant</th>
                                            <!-- <th>PO ID</th> -->
                                            <th>Interior Color</th>
                                            <th>Exterior Color</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($so->vehicles as $vehicle)
                                        <tr>
                                            <td>{{ $vehicle->vin }}</td>
                                            <td>{{ $vehicle->variant->name ?? '-' }}</td>
                                            <!-- <td>{{ $vehicle->purchasing_order_id ?? '-' }}</td> -->
                                            <td>{{ $vehicle->interior->name ?? $vehicle->int_colour }}</td>
                                            <td>{{ $vehicle->exterior->name ?? $vehicle->ex_colour }}</td>
                                        </tr>

                                        @if($vehicle->purchasingOrder)
                                        <tr>
                                            <td colspan="5" class="bg-light">
                                                <strong>PO Number:</strong> {{ $vehicle->purchasingOrder->po_number ?? '-' }} |
                                                <strong>Date:</strong> {{ $vehicle->purchasingOrder->po_date ?? '-' }} |
                                                <strong>Total Cost:</strong> {{ $vehicle->purchasingOrder->totalcost ?? '-' }} {{ $vehicle->purchasingOrder->currency ?? '' }}
                                                @if($vehicle->purchasingOrder->pl_file_path)
                                                <br>
                                                <strong>PO Created By:</strong> {{ $vehicle->purchasingOrder->createdBy->name ?? '-' }} |
                                                <strong>PO Document:</strong>
                                                <a href="{{ asset($vehicle->purchasingOrder->pl_file_path) }}" target="_blank" class="btn btn-primary btn-sm me-2">
                                                    <i class="fa fa-eye"></i> View PO File
                                                </a>
                                                <!-- <a href="{{ asset($vehicle->purchasingOrder->pl_file_path) }}" target="_blank">View File</a> -->
                                                @endif
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        @endif

                                        @endforeach
                                    </tbody>
                                </table>
                                @if($vehicle->incidents->count())
                                <h6 class="mt-3">Incidents</h6>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Remark</th>
                                            <th>Stage</th>
                                            <th>Processing Date</th>
                                            <th>Process Remarks</th>
                                            <th>Reason</th>
                                            <th>File</th>
                                            <th>Detail</th>
                                            <th>Reported Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vehicle->incidents as $i)
                                        <tr>
                                            <td>{{ $i->remark }}</td>
                                            <td>{{ $i->stage }}</td>
                                            <td>{{ $i->processing_date }}</td>
                                            <td>{{ $i->process_remarks }}</td>
                                            <td>{{ $i->reason }}</td>
                                            <td>
                                                @if($i->file_path)
                                                <a href="{{ asset('storage/'.$i->file_path) }}" target="_blank">View</a>
                                                @endif
                                            </td>
                                            <td>{{ $i->detail }}</td>
                                            <td>{{ $i->reported_date }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif


                                @if($vehicle->pdis->count())
                                <h6 class="mt-3">PDI Checks</h6>
                                <ul>
                                    @foreach($vehicle->pdis as $pdi)
                                    <li>{{ $pdi->checking_item }}</li>
                                    @endforeach
                                </ul>
                                @endif


                                @if($vehicle->netsuiteCosts->count())
                                <h6 class="mt-3">NetSuite Costs</h6>
                                <ul>
                                    @foreach($vehicle->netsuiteCosts as $nsc)
                                    <li>{{ $nsc->cost }}</li>
                                    @endforeach
                                </ul>
                                @endif


                                @if($vehicle->purchasingCosts->count())
                                <h6 class="mt-3">Purchasing Costs</h6>
                                <ul>
                                    @foreach($vehicle->purchasingCosts as $pc)
                                    <li>{{ $pc->unit_price }} {{ $pc->currency }}</li>
                                    @endforeach
                                </ul>
                                @endif

                                <!-- @if($vehicle->purchasedOrderChanges->count())
                                <h6 class="mt-3">PO Price Changes</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Original</th>
                                            <th>New</th>
                                            <th>Change</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vehicle->purchasedOrderChanges as $change)
                                        <tr>
                                            <td>{{ $change->original_price }}</td>
                                            <td>{{ $change->new_price }}</td>
                                            <td>{{ $change->price_change }}</td>
                                            <td>{{ $change->change_type }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif -->

                                @php
                                static $shownPurchasingOrders = [];
                                @endphp

                                @if($vehicle->purchasing_order_id && !in_array($vehicle->purchasing_order_id, $shownPurchasingOrders))
                                @php
                                $shownPurchasingOrders[] = $vehicle->purchasing_order_id;

                                // Get all vehicles sharing this PO
                                $vehiclesWithSamePO = $so->vehicles->filter(function ($v) use ($vehicle) {
                                return $v->purchasing_order_id == $vehicle->purchasing_order_id;
                                });

                                $firstMatchingVehicle = $vehiclesWithSamePO->first();

                                // Group changes uniquely by their price + change + type
                                $groupedChanges = collect();
                                if ($firstMatchingVehicle && $firstMatchingVehicle->purchasedOrderChanges) {
                                foreach ($firstMatchingVehicle->purchasedOrderChanges as $change) {
                                $key = "{$change->original_price}-{$change->new_price}-{$change->price_change}-{$change->change_type}";
                                if (!$groupedChanges->has($key)) {
                                $groupedChanges->put($key, $change);
                                }
                                }
                                }
                                @endphp

                                @if($groupedChanges->count())
                                <h6 class="mt-3">PO Price Changes</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Original</th>
                                            <th>New</th>
                                            <th>Change</th>
                                            <th>Type</th>
                                            <th>Vehicle VIN(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groupedChanges as $change)
                                        <tr>
                                            <td>{{ number_format($change->original_price, 2) }}</td>
                                            <td>{{ number_format($change->new_price, 2) }}</td>
                                            <td>{{ number_format($change->price_change, 2) }}</td>
                                            <td>{{ $change->change_type }}</td>
                                            <td>
                                                @foreach($vehiclesWithSamePO as $v)
                                                <span class="badge bg-secondary">{{ $v->vin }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                                @endif


                                @if($vehicle->woVehicle->count())
                                <h6 class="mt-3">WO Vehicle Info</h6>
                                @foreach($vehicle->woVehicle as $wo)
                                <p><strong>Engine:</strong> {{ $wo->engine }}<br>
                                    <strong>Model Desc:</strong> {{ $wo->new_model_description }}<br>
                                    <strong>Warehouse:</strong> {{ $wo->warehouse }}<br>
                                    <strong>Territory:</strong> {{ $wo->territory }}
                                </p>

                                @if($wo->deliveryStatusRelation && is_object($wo->deliveryStatusRelation))
                                <p><strong>GDN Number:</strong> {{ $wo->deliveryStatusRelation->gdn_number }}</p>
                                @endif

                                @endforeach
                                @endif




                            </div>
                            <hr>
                            @endif


                            @if($so->leadClosed)
                            <div class="col-12 mt-3">
                                <h5>Lead Closed Details</h5>
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th>Deal Value</th>
                                            <td>{{ $so->leadClosed->dealvalues ?? '-' }} {{ $so->leadClosed->currency ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sales Note for Lead</th>
                                            <td>{{ $so->leadClosed->sales_notes ?? '-' }}</td>
                                        </tr>
                                        @if($so->leadClosed->call)
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>{{ $so->leadClosed->call->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td>{{ $so->leadClosed->call->phone ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $so->leadClosed->call->email ?? '-' }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            @endif

                            @if($so->soItems->count())
                            <div class="col-12 mt-3">
                                <h5>Quotation Items</h5>
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Total Amount</th>
                                            <th>Model Line</th>
                                            <th>Model Description</th>
                                            <th>Brand</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($so->soItems as $item)
                                        @php $qi = $item->quotationItem; @endphp
                                        @if($qi)
                                        <tr>
                                            <td>{{ $qi->description ?? '-' }}</td>
                                            <td>{{ number_format($qi->unit_price, 2) ?? '-' }}</td>
                                            <td>{{ $qi->quantity ?? '-' }}</td>
                                            <td>{{ number_format($qi->total_amount, 2) ?? '-' }}</td>
                                            <td>{{ $qi->modelLine->model_line ?? '-' }}</td>
                                            <td>{{ $qi->modelDescription->model_description ?? '-' }}</td>
                                            <td>{{ $qi->brand->brand_name ?? '-' }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            @endif

                            @if($so->quotation && $so->quotation->file_path)
                            <h5 class="mt-3">Quotation File</h5>
                            <div class="mb-3">
                                <strong>Document:</strong>
                                <div class="mt-1">
                                    <a href="{{ asset('storage/' . $so->quotation->file_path) }}" target="_blank" class="btn btn-primary btn-sm me-2">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="{{ asset('storage/' . $so->quotation->file_path) }}" download class="btn btn-secondary btn-sm">
                                        <i class="fa fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($so->so_variants->count())
                            <h5>SO Variant Pricing</h5>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Price</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($so->so_variants as $variant)
                                    <tr>
                                        <td>{{ $variant->price }}</td>
                                        <td>{{ $variant->description }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <input type="hidden" name="removed_so_ids" id="removed_so_ids">
            <input type="hidden" name="linked_so_number" value="{{ $displaySoNumber }}">

            <div class="form-group mt-3">
                <label for="remark"><strong>Remarks (Optional):</strong></label>
                <textarea name="remarks" class="form-control" rows="3" placeholder="Add remarks here..."></textarea>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success">Finalize Selected SO</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('finalize-so-form').addEventListener('submit', function(e) {
        const selected = document.querySelector('input[name="finalized_so_id"]:checked');
        if (!selected) {
            e.preventDefault();
            alert('Please select one SO ID to finalize.');
            return;
        }

        const allIds = [...document.querySelectorAll('input[name="finalized_so_id"]')].map(input => input.value);
        const removedIds = allIds.filter(id => id !== selected.value);
        document.getElementById('removed_so_ids').value = JSON.stringify(removedIds);
    });
</script>
@endpush