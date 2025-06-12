@extends('layouts.table')
@section('content')
<style>
    .input-wrapper {
        display: flex;
        align-items: center;
    }

    .prefix {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        padding: 6px 10px;
        border-right: none;
        border-radius: 4px 0 0 4px;
        font-weight: bold;
        color: #495057;
    }

    .input-field {
        border-radius: 0 4px 4px 0;
        flex: 1;
    }

    .error-message {
        color: red;
        font-size: 0.9em;
    }

    .widthinput {
        height: 34px !important;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(128, 128, 128, 0.5);
        display: none;
    }

    /* Improved table responsiveness */
    .table-responsive {
        overflow-x: auto;
        padding: 0;
        margin: 0;
    }

    /* Table style adjustments */
    .table th,
    .table td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 0.75rem;
    }
</style>
@can('edit-so')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
@endphp
@if ($hasPermission)
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            Update Sales Order
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </h4>
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
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
        <form action="{{ route('salesorder.update', $so->id) }}" id="form-update" method="POST">
            @csrf
            @method('PUT')
            <div class="row gy-3">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="row align-items-center">
                        <div class="col-4 col-md-4">
                            <strong>Document Type:</strong>
                        </div>
                        <div class="col-8 col-md-8">
                            <div>
                                <label class="form-check-label" for="inlineCheckbox2">
                                    {{$quotation->document_type}} To Sales Order
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="row align-items-center">
                        <div class="col-4 col-md-4">
                            <strong>Category:</strong>
                        </div>
                        <div class="col-8 col-md-8">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="row align-items-center">
                        <div class="col-4 col-md-4">
                            <strong>Currency:</strong>
                        </div>
                        <div class="col-8 col-md-8">
                            <label class="form-check-label" for="inlineCheckbox2">{{$quotation->currency}}</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class=" mt-4">
                <div class="row gy-3">
                    <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <strong>Client's Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Client Category:</strong></div>
                                    <div class="col-sm-6">
                                        @if(!$call->company_name)
                                        <label class="form-check-label">Individual</label>
                                        @else
                                        <label class="form-check-label">Company</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2" id="contact-person-div">
                                    <div class="col-sm-6"><strong>Contact Person:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$call->name}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2" id="company-div">
                                    <div class="col-sm-6"><strong>Company:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$call->company_name}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Customer:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$call->name}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Contact Number:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$call->phone}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Email:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$call->email}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Address:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$call->address}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <strong>Document Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Document Validity:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$customerdetails->document_validity}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Sales Person:</strong></div>
                                    <div class="col-sm-6">{{ $saleperson->name ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Sales Office:</strong></div>
                                    <div class="col-sm-6">{{ isset($empProfile->office) ? $empProfile->office : '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Sales Email ID:</strong></div>
                                    <div class="col-sm-6">{{ $saleperson->email ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Sales Contact No:</strong></div>
                                    <div class="col-sm-6">{{ isset($empProfile->phone) ? $empProfile->phone : '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <strong>Delivery Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Final Destination:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">
                                            @if ($customerdetails->country)
                                            {{ $customerdetails->country->name }}
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Incoterm:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$customerdetails->incoterm}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Port of Discharge:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">
                                            @if ($customerdetails->shippingPort)
                                            {{$customerdetails->shippingPort->name ?? ''}}
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Port of Loading:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">
                                            @if ($customerdetails->shippingPortOfLoad)
                                            {{$customerdetails->shippingPortOfLoad->name ?? ''}}
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class=" mt-4">
                <div class="row gy-3">
                    <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <strong>Payment Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Payment Terms:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">
                                            @if ($customerdetails->paymentterms)
                                            {{$customerdetails->paymentterms->name}}
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-2" id="advance-amount-div" hidden>
                                    <div class="col-sm-6"><strong>Advance Amount:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$customerdetails->advance_amount}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <strong>Client's Representative</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Rep Name:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$customerdetails->representative_name}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Rep No:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$customerdetails->representative_number}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-header">
                                        <strong></strong>
                                    </div>
                                    <div class="card-body text-center">
                                        <p class="text-muted"></p>
                                    </div>
                                </div>
                            </div> -->
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="row mt-2">
                        <div class="col-sm-2">
                            <strong>Remarks :</strong>
                        </div>
                        <div class="col-sm-10">
                            <label class="form-check-label" for="inlineCheckbox2">{{$quotation->sales_notes}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>SO Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <label for="today_date"><strong>SO Date</strong></label>
                                    <input type="date" class="form-control" id="so_date" name="so_date" value="{{$so->so_date}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                <label for="so_number"><span class="text-danger">* </span> Netsuit SO Number</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">SO-</span>
                                        <input type="text" class="form-control" placeholder="Enter SO Number" id="so_number" name="so_number"
                                            value="{{ preg_replace('/^SO-/', '', $so->so_number) }}" aria-label="Enter SO Number">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="text_area"><strong>Sales Notes</strong></label>
                                    <textarea class="form-control" id="notes" name="notes">{{$so->notes}}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sales Order Vehicles</h4>
                </div>
                <div class="card-body">
                    <div class="card" style="background-color:#fafaff; border-color:#e6e6ff;">
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <h5>Total Vehicles - {{ $totalVehicles }}</h5>
                                    <div class="col-md-12 mt-3" id="so-vehicles">
                                        @foreach($soVariants as $key => $soVariant)
                                        <div class="so-variant-add-section " id="variant-section-{{ $key + 1 }}">
                                            <div class="row">
                                                <div class="mb-2 col-sm-12 col-md-3 col-lg-3 col-xxl-3">
                                                <span class="text-danger">* </span><label class="form-label font-size-13">Choose Variant</label>
                                                    <select name="variants[{{$key+1}}][variant_id]" required index="{{$key+1}}" id="variant-{{ $key+1 }}"
                                                        multiple class="variants form-control" data-is-gdn="{{ $soVariant->isgdnExist }}">
                                                        @foreach($variants as $variant)
                                                        <option value="{{ $variant->id }}" {{ $variant->id == $soVariant->variant_id ? 'selected' : '' }}>{{ $variant->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="variants[{{ $key + 1 }}][so_variant_id]" class="so-variants" index="{{ $key+1 }}"
                                                        value="{{ $soVariant->id}}" id="so-variant-{{ $key+1 }}">

                                                </div>
                                                <div class="mb-2 col-sm-12 col-md-4 col-lg-4 col-xxl-4">
                                                <span class="text-danger">* </span><label class="form-label font-size-13">Description</label>
                                                    <input type="text" class="variant-descriptions form-control widthinput" name="variants[{{ $key + 1 }}][description]" index="{{$key+1}}"
                                                        id="variant-description-{{ $key+1 }}" required value="{{ $soVariant->description ?? '' }}" placeholder="Decsription" />
                                                </div>
                                                <div class="col-sm-12 col-md-2 col-lg-2 col-xxl-2">
                                                <span class="text-danger">* </span><label class="form-label font-size-13">Price</label>
                                                    <input type="number" class="form-control variant-prices widthinput" required name="variants[{{$key+1}}][price]" placeholder="Price"
                                                        value="{{ $soVariant->price }}" id="price-{{ $key+1 }}" index="{{$key+1}}" min="0">
                                                </div>
                                                <div class="col-sm-12 col-md-2 col-lg-2 col-xxl-2">
                                                <span class="text-danger">* </span><label class="form-label font-size-13">Quantity</label>
                                                    <input type="number" class="form-control variant-quantities widthinput" required index="{{$key+1}}" min="1"
                                                        name="variants[{{$key+1}}][quantity]" placeholder="Quantity" value="{{ $soVariant->quantity }}" id="quantity-{{ $key+1 }}">
                                                </div>
                                                <div class="col-sm-12 col-md-1 col-lg-1 col-xxl-1">
                                                    <a class="btn btn-sm btn-danger removeVariantButton" index="{{ $key+1}}" style="margin-top: 31px;" data-variant-id="{{ $soVariant->id}}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-11 col-lg-11 col-xxl-11 mb-4 ms-5">
                                                    <label class="form-label font-size-13">Choose VIN</label>
                                                    <select name="variants[{{$key+1}}][vehicles][]" id="vin-{{ $key+1 }}" index="{{$key+1}}" class="vins form-control" multiple>
                                                        @foreach($soVariant->soVehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}"
                                                            {{ in_array($vehicle->id, $soVariant->selectedVehicleIds) ? 'selected' : '' }}
                                                            {{ $vehicle->gdn_id ? 'data-lock=true' : '' }}>{{ $vehicle->vin ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="row ">
                                        <div class="col-12">
                                            <div class="btn btn-info btn-sm add-variant-btn float-end mt-2">
                                                <i class="fas fa-plus"></i> Add Variant
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Payments</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="currency"><strong>Currency</strong></label>
                                <input type="text" class="form-control" id="currency" name="currency" value="{{$quotation->currency}}" readonly>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="total_payment"><strong>Total Payment</strong></label>
                                <input type="number" class="form-control" readonly id="total_payment" name="total_payment" value="{{$so->total}}" min="0">
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="receiving_payment"><strong>Total Receiving Payment</strong></label>
                                <input type="number" class="form-control" id="receiving_payment" name="receiving_payment" readonly value="{{$so->receiving}}" min="0">
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="advance_payment_performa"><strong>Payment In Performa</strong></label>
                                <input type="number" class="form-control payment" id="advance_payment_performa" name="advance_payment_performa" value="{{$so->paidinperforma}}" min="0">
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                            <span class="text-danger">* </span><label for="payment_so"><strong>Payment In SO</strong></label>
                                <input type="number" class="form-control payment" id="payment_so" name="payment_so" value="{{$so->paidinso}}" required min="0">
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="balance_payment"><strong>Balance Payment</strong></label>
                                @php
                                $balance = $so->total - $so->receiving;
                                @endphp
                                <input type="number" class="form-control" id="balance_payment" value="{{$balance}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </br>
            </br>
            <div id="deleted-ids"></div>

            <button type="submit" class="btn btn-primary btn-submit">Submit</button>
        </form>
        <div class="card mt-3 shadow-sm">
            <div class="card-header text-white">
                <h6>Sales Order Log Histories</h6>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table id="so-logs" class="table table-striped table-editable table-edits table table-condensed"
                        style="width:100%;">
                        <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.NO</th>
                                <th>Version</th>
                                <th>Variant</th>
                                <th>Field Name</th>
                                <th>Type</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Created Date</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @can('approve-so')
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('approve-so');
        @endphp
        @if ($hasPermission)
        @if($so->status == 'Pending')
        <div class="row mt-2">
            <div class="col d-flex gap-2">
                <button type="button" class="btn btn-success btn-approve" data-id="{{ $so->id }}" data-status="Approved">Approve</button>
                <button type="button" class="btn btn-danger"
                    data-bs-toggle="modal" data-bs-target="#rejectModal" data-status="Rejected">Reject</button>
            </div>
        </div>
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Sales Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="rejectForm">
                            <div class="mb-3">
                            <span class="text-danger">* </span><label for="reason" class="form-label">Rejection Reason</label>
                                <textarea id="reason" class="form-control" rows="4" placeholder="Enter reason for rejection" required></textarea>
                                <input type="hidden" name="so_id">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-reject" data-id="{{ $so->id }}"
                            data-status="Reject">Reject</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
        @endcan
    </div>
</div>
<div class="overlay"></div>

@endif
@endcan

@endsection
@push('scripts')
<script>
    let soId = '{{ $so->id }}';
    let QuotaionItemCount = '{{ $soVariants->count() }}';
    let isFormValid = 0;
    let variantsData = @json($variants);
    let deletedVariantIds = [];

    function normalizeSpacing(str) {
        return str ? str.replace(/\s+/g, ' ').trim() : '';
    }

    $(document).ready(function() {
        // Initialize with normalized spacing for existing descriptions
        $('.variant-descriptions').each(function() {
            $(this).val(normalizeSpacing($(this).val()));
        });

        // for input for variant descriptions
        $(document).on('input', '.variant-descriptions', function() {
            $(this).val(normalizeSpacing($(this).val()));
        });

        // Prevent negative input via keyboard for all payment fields
        $(document).on('keydown', '#total_payment, #receiving_payment, #advance_payment_performa, #payment_so', function(e) {
            if (e.key === '-' || e.key === 'e') {
                e.preventDefault();
            }
        });

        // Ensure no negative values on change for all payment fields
        $(document).on('change', '#total_payment, #receiving_payment, #advance_payment_performa, #payment_so', function() {
            if ($(this).val() < 0) {
                $(this).val(0);
            }
        });

        // Prevent negative input via keyboard for prices and quantities
        $(document).on('keydown', '.variant-prices, .variant-quantities', function(e) {
            if (e.key === '-' || e.key === 'e') {
                e.preventDefault();
            }
        });

        // Ensure no negative values on change for prices and quantities
        $(document).on('change', '.variant-prices, .variant-quantities', function() {
            if ($(this).val() < 0) {
                $(this).val(0);
            }
        });

        var table1 = $('#so-logs').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('salesorder.edit', $so->id) }}",
                type: 'GET',
                data: function(d) {
                    d.draw = d.draw || 1;
                    d.start = d.start || 0;
                    d.length = d.length || 10;
                },
                error: function (xhr, error, thrown) {
                    console.error('DataTable error:', error);
                    alertify.error('Error loading log history data');
                }
            },
            columns: [{
                    'data': 'DT_RowIndex',
                    'name': 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    'data': 'version',
                    'name': 'version',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    'data': 'so_variant_id',
                    'name': 'SoVariant.variant.name',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    'data': 'field_name',
                    'name': 'field_name',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    'data': 'type',
                    'name': 'type',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    'data': 'old_value',
                    'name': 'old_value',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    'data': 'new_value',
                    'name': 'new_value',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    'data': 'created_at',
                    'name': 'created_at',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data ? moment(data).format('YYYY-MM-DD HH:mm:ss') : '-';
                    }
                },
                {
                    'data': 'created_by',
                    'name': 'created_by',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data || '-';
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
            }
        });

        $('.vins').select2({
            placeholder: 'Select VIN',
        });
        for (let i = 1; i <= QuotaionItemCount; i++) {
            let index = $('#vin-' + i).attr('index');
            initializeVinSelect2(index)
        }

        $('.variants').select2({
            placeholder: 'Select Variant',
            maximumSelectionLength: 1
        });
        // $(document.body).on('select2:unselect', ".vins", function (e) {
        $(document.body).on('select2:unselecting', ".vins", function(e) {

            var $option = $(e.params.args.data.element);
            if ($option.data('lock')) {
                e.preventDefault();
                alertify.confirm('This vehicle cannot be removed because it has a GDN assigned.').set({
                    title: "Can't Remove this VIN"
                });
            } else {
                // append vin from another dropdown
                let index = $(this).attr('index');
                let unSelectedvin = e.params.args.data.id;
                let vinText = e.params.args.data.text;
                let variant = $('#variant-' + index).val();
                appendVin(index, unSelectedvin, vinText, variant[0])
            }
        });
        $(document.body).on('select2:select', ".vins", function(e) {
            let index = $(this).attr('index');
            let vin = e.params.data.id;
            hideVin(index, vin);
        });
    });

    function formatVariantData(data) {
        return data.map(variant => ({
            id: variant.id,
            text: variant.name
        }));
    }

    function ReinitializeSelect2(selector) {

        if ($(selector).data('select2')) {
            $(selector).select2('destroy');
        }
        $(selector).select2({
            placeholder: 'Select Variant',
            maximumSelectionLength: 1,
            data: formatVariantData(variantsData),
        });
    }

    $('.btn-approve').on('click', function() {
        let soid = $(this).attr('data-id');
        let status = $(this).attr('data-status');

        var confirm = alertify.confirm('Are you sure you want to approve this so?', function(e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('so.approveOrReject') }}",
                    dataType: "json",
                    data: {
                        so_id: soid,
                        status: status,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            alertify.success(response.message);
                            window.location.href = response.redirect; // Redirect if needed
                        } else {
                            alertify.error(response.message);
                        }
                    }
                });
            }
        }).set({
            title: "So Approval"
        })
    });

    $('.btn-reject').on('click', function() {
        let soid = $(this).attr('data-id');
        let status = $(this).attr('data-status');
        let reason = $('#reason').val();

        var confirm = alertify.confirm('Are you sure you want to reject this so?', function(e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('so.approveOrReject') }}",
                    dataType: "json",
                    data: {
                        so_id: soid,
                        status: status,
                        reason: reason,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            alertify.success(response.message);
                            window.location.href = response.redirect; // Redirect if needed
                        } else {
                            alertify.error(response.message);
                        }
                    }
                });
            }
        }).set({
            title: "So Approval"
        })
    });

    // validation start //
    const originalSoNumber = "{{ preg_replace('/^SO-/', '', $so->so_number) }}";

    $.validator.addMethod("uniqueSO", function(value, element, param) {
        // If the SO number hasn't changed, no need to check uniqueness
        if (value === originalSoNumber) { 
            return true; 
        }
        
        // Return if value is empty or not 6 digits
        if (!value || !/^\d{6}$/.test(value)) {
            return true;
        }

        // Check if we already validated this value
        let $element = $(element);
        let lastValue = $element.data('lastCheckedValue');
        let lastResult = $element.data('lastCheckResult');
        
        if (lastValue === value) {
            return lastResult;
        }

        let isUnique = false;
        $.ajax({
            url: '/so-unique-check',
            type: 'GET',
            data: {
                so_number: "SO-" + value,
                so_id: soId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            success: function(response) {
                isUnique = !response.exists;
                // Store the result for this value
                $element.data('lastCheckedValue', value);
                $element.data('lastCheckResult', isUnique);
            }
        });
        return isUnique;
    }, "SO Number already exists. Please enter a different one.");

    $.validator.addMethod("spacing", function(value, element) {
        return this.optional(element) || !/\s\s+/.test(value);
    }, "No more than one consecutive space is allowed in the description");

    $.validator.addMethod("onlyDigitsNoSpaces", function(value, element) {
        return this.optional(element) || /^\d{6}$/.test(value);
    });

    $("#form-update").validate({
        onsubmit: true,
        onfocusout: function(element) {
            if (element.name === 'so_number') {
                let tempRules = { onlyDigitsNoSpaces: true };
                $(element).rules('remove', 'uniqueSO');
                $(element).valid();
                $(element).rules('add', { uniqueSO: true });
            }
        },
        onkeyup: false,
        onclick: false,
        submitHandler: function(form) {
            if ($(form).valid()) {
                form.submit();
            }
        },
        showErrors: function(errorMap, errorList) {
            this.defaultShowErrors();
        },
        ignore: [],
        rules: {
            so_number: {
                required: true,
                uniqueSO: true,
                onlyDigitsNoSpaces: true
            },
            "variants[*][variant_id]": {
                required: true
            },
            "variants[*][description]": {
                required: true,
                spacing: true
            },
            "variants[*][price]": {
                required: true,
                number: true,
                min: 0
            },
            "variants[*][quantity]": {
                required: true
            },
        },
        messages: {
            so_number: {
                required: "SO Number is required",
                onlyDigitsNoSpaces: "Only 6 numbers are allowed. No letters, symbols, or spaces."
            },
            "variants[*][price]": {
                required: "Price is required",
                number: "Please enter a valid number",
                min: "Price cannot be negative"
            }
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids()) {
                return;
            }
        }
    });

    // Format validation on input
    $('#so_number').on('keyup blur', function(e) {
        e.stopPropagation();
        if (!/^\d{6}$/.test($(this).val()) && $(this).val() !== '') {
            $(this).addClass('error');
        } else {
            $(this).removeClass('error');
        }
    });

    /// validation end ///

    $('.add-variant-btn').click(function() {
        var index = $("#so-vehicles").find(".so-variant-add-section").length + 1;

        var newRow = `
                 <div class="so-variant-add-section" id="variant-section-${index}">
                 
                    <div class="row">
                        <div class="mb-2 col-sm-12 col-md-3 col-lg-3 col-xxl-3">
                         <span class="text-danger">* </span><label class="form-label font-size-13">Choose Variant</label>
                         
                            <select name="variants[${index}][variant_id]" index="${index}" id="variant-${index}"
                             class="variants form-control" multiple required  data-is-gdn="0">
                            </select>
                            <input type="hidden" name="variants[${index}][so_variant_id]" class="so-variants" index="${index}"
                                value="" id="so-variant-${index}">
                        </div> 
                        <div class="mb-2 col-sm-12 col-md-4 col-lg-4 col-xxl-4">
                           <span class="text-danger">* </span> <label class="form-label font-size-13">Description</label>
                            <input type="text" class="variant-descriptions form-control widthinput" name="variants[${index}][description]" index="${index}"
                              required id="variant-description-${index}" placeholder="Decsription" />
                        </div>
                        <div class="col-sm-12 col-md-2 col-lg-2 col-xxl-2">
                          <span class="text-danger">* </span><label class="form-label font-size-13">Price</label>
                        <input type="number" class="form-control variant-prices widthinput" required name="variants[${index}][price]" placeholder="Price"
                         id="price-${index}" index="${index}" min="0">
                        </div>
                        <div class="col-sm-12 col-md-2 col-lg-2 col-xxl-2">
                          <span class="text-danger">* </span><label class="form-label font-size-13">Quantity</label>
                        <input type="number" class="form-control variant-quantities widthinput" required index="${index}" min="1" value="1" placeholder="Quantity"
                            name="variants[${index}][quantity]" id="quantity-${index}"  >
                        </div>
                        <div class="col-sm-12 col-md-1 col-lg-1 col-xxl-1">
                            <a class="btn btn-sm btn-danger removeVariantButton" index="${index}" style="margin-top: 31px;" >
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-11 col-lg-11 col-xxl-11 mb-4 ms-5">
                            <label class="form-label font-size-13">Choose VIN</label>
                            <select name="variants[${index}][vehicles][]" id="vin-${index}" index="${index}" class="vins form-control" multiple >
                                
                            </select>
                        </div> 
                    </div>
                </div>`;

        $('#so-vehicles').append(newRow);

        $('#vin-' + index).select2({
            placeholder: 'Select Vin',
            maximumSelectionLength: 1,
        });
        // getSOVariants(index);
        ReinitializeSelect2('#variant-' + index);
    });

    $(document.body).on('select2:select', ".variants", function(e) {
        $('.overlay').show();
        var index = $(this).attr('index');
        let url = '{{ route('so.getVins') }}';
        let variant = $('#variant-' + index).val();
        let totalIndex = $("#so-vehicles").find(".so-variant-add-section").length;
        var selectedVinIds = [];

        for (let i = 1; i < totalIndex; i++) {
            var selectedOptions = $('#vin-' + i).val();

            if (selectedOptions && selectedOptions.length > 0) {
                selectedVinIds = selectedVinIds.concat(selectedOptions);
            }
        }
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            data: {
                variant_id: variant[0],
                so_id: soId,
                selectedVinIds: selectedVinIds
            },
            success: function(data) {
                $('#vin-' + index).empty();
                $('#vin-' + index).html('<option value=""> Select Vin </option>');
                jQuery.each(data.vehicles, function(key, value) {
                    $('#vin-' + index).append('<option value="' + value.id + '">' + value.vin + '</option>');
                });
                $('#variant-description-' + index).val(data.variant_description);
                $('.overlay').hide();
            }
        });
    });

    $(document.body).on('select2:unselect', ".variants", function(e) {
        var index = $(this).attr('index');
        let variant = e.params.data.id;
        let variantText = e.params.data.text;

        var isGdn = $(this).attr('data-is-gdn');
        if (isGdn == 1) {
            e.preventDefault();
            alertify.confirm('This Variant cannot be removed because it has a GDN assigned vehicles.').set({
                title: "Can't Remove this Variant"
            });
            $('#variant-' + index).val(variant).trigger('change');
        } else {
            selectedVinCount = $('#vin-' + index).val()?.length;
            if (selectedVinCount > 0) {
                var confirm = alertify.confirm('unselecting of varaint will leads to removal of related vins!', function(e) {
                    if (e) {
                        resetVin(index, variant, variantText);
                    }
                }).set({
                    title: "Are You Sure?"
                }).set('oncancel', function(closeEvent) {
                    $('#variant-' + index).val(variant).trigger('change');
                });
            } else {
                resetVin(index, variant, variantText);
            }
        }
    });

    function resetVin(index, variant, variantText) {
        $('#variant-description-' + index).val('');
        $('#vin-' + index).empty();
    }

    // function getSOVariants(index){
    //     let totalIndex =  $("#so-vehicles").find(".so-variant-add-section").length;
    //     let url = '{{ route('so.getVariants') }}';
    //     var selectedVariantIds = [];
    //     for(let i=1; i< totalIndex; i++)
    //     {
    //         var eachselectedVariantId = $('#variant-'+i).val();
    //         if(eachselectedVariantId) {
    //             selectedVariantIds.push(eachselectedVariantId);
    //         }
    //     }
    //     $.ajax({
    //         type: "GET",
    //         url: url,
    //         dataType: "json",
    //         data: {
    //             selectedVariantIds:selectedVariantIds,
    //         },
    //         success:function (data) {  

    //             let variantDropdownData = [];
    //             $.each(data,function(key,value){
    //                 variantDropdownData.push
    //                 ({
    //                     id: value.id,
    //                     text: value.name
    //                 });
    //             });
    //                 $('#variant-' + index).html("");
    //                 $('#variant-' + index).select2({
    //                     placeholder: 'Select Variant',
    //                     data: variantDropdownData,
    //                     maximumSelectionLength: 1,
    //                 });
    //         }
    //     }); 
    // }

    $(document.body).on('click', ".removeVariantButton", function(e) {
        var rowCount = $("#so-vehicles").find(".so-variant-add-section").length;
        var indexNumber = $(this).attr('index');
        var soVariantId = $(this).attr('data-variant-id');

        if (rowCount > 1) {
            var selectedVins = $('#vin-' + indexNumber).val();
            var isGdn = $('#variant-' + indexNumber).attr('data-is-gdn');

            if (isGdn == 1) {
                e.preventDefault();
                alertify.confirm('This Variant cannot be removed because it has a GDN assigned vehicles.').set({
                    title: "Can't Remove this Variant"
                });
            } else {
                if (Array.isArray(selectedVins) && selectedVins.length > 0) {
                    e.preventDefault();
                    alertify.confirm('Are you sure to remove this? You\'ll lose all the selected VINs.',
                        function () {
                            if (soVariantId !== undefined && !deletedVariantIds.includes(soVariantId)) {
                                deletedVariantIds.push(soVariantId);
                                $('#deleted-ids').append(
                                    `<input type="hidden" name="deleted_so_variant_ids[]" value="${soVariantId}">`
                                );
                            }
                            $('#vin-' + indexNumber).closest(".so-variant-add-section").remove();
                        }
                    ).set({
                        title: "VINs are Selected"
                    });
                    return;
                }

                if (soVariantId !== undefined && !deletedVariantIds.includes(soVariantId)) {
                    deletedVariantIds.push(soVariantId);
                    $('#deleted-ids').append(
                        `<input type="hidden" name="deleted_so_variant_ids[]" value="${soVariantId}">`
                    );
                }

                $(this).closest('#variant-section-' + indexNumber).remove();
            }
        } else {
            var confirm = alertify.confirm('You are not able to remove this row, Atleast one Variant is Required', function(e) {}).set({
                title: "Can't Remove Variant"
            });
        }
    });

    function reindexVariants() {
        $('.so-variant-add-section').each(function(i) {
            var index = i + 1;
            $(this).find('.variant-descriptions').attr({
                'index': index,
                'id': 'variant-description-' + index,
                'name': 'variants[' + index + '][description]'
            });

            $(this).attr('id', 'variant-section-' + index);

            $(this).find('.variants').attr({
                'index': index,
                'id': 'variant-' + index,
                'name': 'variants[' + index + '][variant_id]'
            });

            $(this).find('.so-variants').attr({
                'index': index,
                'id': 'so-variant-' + index,
                'name': 'variants[' + index + '][so_variant_id]'
            });

            $(this).find('.variant-prices').attr({
                'index': index,
                'id': 'price-' + index,
                'name': 'variants[' + index + '][price]'
            });

            $(this).find('.variant-quantities').attr({
                'index': index,
                'id': 'quantity-' + index,
                'name': 'variants[' + index + '][quantity]'
            });

            $(this).find('.vins').attr({
                'index': index,
                'id': 'vin-' + index,
                'name': 'variants[' + index + '][vehicles][]'
            });

            $(this).find('.removeVariantButton').attr('index', index);

            $('#vin-' + index).select2('destroy');
            ReinitializeSelect2('#variant-' + index);
            $('#vin-' + index).select2({
                placeholder: 'Select Vin',
            });
        });
    }

    function hideVin(index, vin) {
        var totalIndex = $("#so-vehicles").find(".so-variant-add-section").length;
        for (let i = 1; i <= totalIndex; i++) {
            if (i != index) {
                var currentId = 'vin-' + i;
                $('#' + currentId + ' option[value=' + vin + ']').detach();
            }
        }
    }

    function appendVin(index, unSelectedVin, vinText, variant) {
        var totalIndex = $("#so-vehicles").find(".so-variant-add-section").length;
        for (let i = 1; i <= totalIndex; i++) {
            let currentVariant = $('#variant-' + i).val();
            // if same variant then only need to append vin
            if (i != index && currentVariant[0] == variant) {
                let selectedVins = $('#vin-' + i).val() || [];
                if (!selectedVins.includes(unSelectedVin)) {
                    console.log("notexisting in list");
                    let selectId = 'vin-' + i;
                    let optionExists = false;

                    $('#' + selectId + ' option').each(function() {
                        if ($(this).val() == unSelectedVin) {
                            optionExists = true;
                            return false;
                        }
                    });
                    if (!optionExists) {
                        $('#' + selectId).append($('<option>', {
                            value: unSelectedVin,
                            text: vinText
                        }));
                    }
                }
            }
        }
    }

    function initializeVinSelect2(index) {
        const quantity = parseInt($('#quantity-' + index).val()) || 1;
        $('#vin-' + index).select2('destroy').select2({
            placeholder: 'Select Vin',
            maximumSelectionLength: quantity,
        });
    }

    $(document).on('input', '.variant-quantities', function() {
        const index = $(this).attr('index');
        initializeVinSelect2(index);
        ValidateVinwithQty();
        calculateTotalSOAmount();
    });
    $('.btn-submit').click(function(e) {
        e.preventDefault();
        ValidateVinwithQty();
        if (isFormValid == 0) {
            if ($("#form-update").valid()) {
                $('#form-update').unbind('submit').submit();
            }
        }

    });

    function ValidateVinwithQty() {
        var totalIndex = $("#so-vehicles").find(".so-variant-add-section").length;
        isFormValid = 0;
        for (let i = 1; i <= totalIndex; i++) {
            let qty = $('#quantity-' + i).val();
            let selectedVinCount = $('#vin-' + i).val()?.length || 0;
            if (selectedVinCount > qty) {
                let select2Container = $('#vin-' + i).next('.select2');
                select2Container.find('.vin-error-message').remove();
                isFormValid = 1;
                select2Container.append(`
                            <span class="vin-error-message" style="color: red; font-size: 14px; margin-top: 4px; display: block;">
                                The chosen vin count exceeding the variant quantity (${qty}).
                            </span>
                        `);
                return false;
            }

        };
    }

    $(document).on('input', '.variant-prices', function() {
        calculateTotalSOAmount();
    });

    function calculateTotalSOAmount() {
        var sum = 0;
        $('.variant-prices').each(function() {
            var index = $(this).attr('index');
            var quantity = $('#quantity-' + index).val();
            var unitPrice = $('#price-' + index).val();
            var eachItemTotal = parseFloat(quantity) * parseFloat(unitPrice);

            sum = sum + eachItemTotal;
        });
        $('#total_payment').val(sum);
    }

    function updateTotalReceivingPayment() {
        var paymentPerforma = parseFloat(document.getElementById('advance_payment_performa').value) || 0;
        var paymentSO = parseFloat(document.getElementById('payment_so').value) || 0;
        // Ensure payment_so is not negative
        if (paymentSO < 0) {
            document.getElementById('payment_so').value = 0;
            paymentSO = 0;
        }
        var totalReceivingPayment = paymentPerforma + paymentSO;
        document.getElementById('receiving_payment').value = totalReceivingPayment.toFixed(2);
    }

    function updateBalancePayment() {
        var totalPayment = parseFloat(document.getElementById('total_payment').value) || 0;
        var totalReceivingPayment = parseFloat(document.getElementById('receiving_payment').value) || 0;
        var balancePayment = totalPayment - totalReceivingPayment;
        document.getElementById('balance_payment').value = balancePayment.toFixed(2);
    }
    document.querySelectorAll('.payment').forEach(function(element) {
        element.addEventListener('input', function() {
            if (this.id === 'payment_so' && parseFloat(this.value) < 0) {
                this.value = 0;
            }
            updateTotalReceivingPayment();
            updateBalancePayment();
        });
    });

    // JavaScript code to check for duplicate VINs
    // function checkForDuplicateVINs() {
    //     var selectedVINs = {};
    //     var dropdowns = document.querySelectorAll('select[name^="vehicle_vin"]');

    //     for (var i = 0; i < dropdowns.length; i++) {
    //         var selectedOption = dropdowns[i].value;

    //         if (selectedOption && selectedOption !== '') {
    //             if (selectedVINs[selectedOption]) {
    //                 // Duplicate VIN found, display an error message and prevent form submission
    //                 alert('Duplicate VIN ' + selectedOption + ' selected. Please select a unique VIN for each vehicle.');
    //                 return false; // Prevent form submission
    //             }
    //             selectedVINs[selectedOption] = true;
    //         }
    //     }
    //     return true; // No duplicate VINs found, allow form submission
    // }



    const soInput = document.getElementById('so_number');
    const errorMessage = document.getElementById('error_message');

    soInput.addEventListener('input', function() {
        const regex = /^\d{6}$/; // Pattern: Exactly 6 digits
        const value = soInput.value;

        if (!regex.test(value)) {
            errorMessage.textContent = "Please enter exactly 6 digits after 'SO-' (e.g., 007362).";
            soInput.setCustomValidity("Invalid");
        } else {
            errorMessage.textContent = "";
            soInput.setCustomValidity("");
        }
    });
</script>
@endpush