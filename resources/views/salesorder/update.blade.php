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
</style>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            Update Sales Order
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </h4>
    </div>
    <div class="card-body">
        <form onsubmit="return checkForDuplicateVINs();" action="{{ route('salesorder.storesalesorderupdate', ['QuotationId' => $quotation->id]) }}" id="form-create" method="POST">
            @csrf
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
                                        @if(!$calls->company_name)
                                        <label class="form-check-label">Individual</label>
                                        @else
                                        <label class="form-check-label">Company</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2" id="contact-person-div">
                                    <div class="col-sm-6"><strong>Contact Person:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$calls->name}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2" id="company-div">
                                    <div class="col-sm-6"><strong>Company:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$calls->company_name}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Customer:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$calls->name}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Contact Number:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$calls->phone}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Email:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$calls->email}}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Address:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">{{$calls->address}}</label>
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
                                    <div class="col-sm-6">{{ $saleperson->name }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Sales Office:</strong></div>
                                    <div class="col-sm-6">{{ isset($empProfile->office) ? $empProfile->office : '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Sales Email ID:</strong></div>
                                    <div class="col-sm-6">{{ $saleperson->email }}</div>
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
                                            {{$customerdetails->shippingPort->shipping_port_id}}
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6"><strong>Port of Loading:</strong></div>
                                    <div class="col-sm-6">
                                        <label class="form-check-label">
                                            @if ($customerdetails->shippingPortOfLoad)
                                            {{$customerdetails->shippingPortOfLoad->to_shipping_port_id}}
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
                                    <input type="date" class="form-control" id="so_date" name="so_date" value="{{$sodetails->so_date}}">
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <label for="text_input"><strong>Netsuit SO Number</strong></label>
                                    <div class="input-wrapper d-flex align-items-center">
                                        <span class="prefix">SO-</span>
                                        <input type="text" class="form-control input-field" id="so_number" name="so_number"
                                            placeholder="Enter SO Number"
                                            value="{{ preg_replace('/^SO-/', '', $sodetails->so_number) }}">
                                    </div>
                                    <span id="error_message" class="text-danger"></span>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="text_area"><strong>Sales Notes</strong></label>
                                    <textarea class="form-control" id="notes" name="notes">{{$sodetails->notes}}</textarea>
                                </div>
                                <!-- <div class="col-12 mt-3">
                                <a href="{{ route('qoutation.proforma_invoice_edit', ['callId' => $calls->id]) }}" 
                                    class="btn btn-warning">
                                        Reopen Quotation
                                    </a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                @php
                // Calculate the total number of vehicles
                $totalVehicles = $quotationItems->sum('quantity');
                @endphp
                <h6>Vehicles - Total Vehicles ({{ $totalVehicles }})</h6>
                <div class="col-md-12">
                    @foreach($quotationItems as $quotationItem)
                    <div class="mb-1">
                        <h6>{{ $loop->iteration }} - {{ $quotationItem->description }} - ({{ $quotationItem->quantity }})</h6>
                        <div class="row">
                            @for ($i = 0; $i < $quotationItem->quantity; $i++)
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    @php
                                    // Get the corresponding so_item for this specific loop iteration
                                    $soItem = $soitems->where('quotation_items_id', $quotationItem->id)->skip($i)->first();
                                    $soItemId = $soItem ? $soItem->id : null;
                                    $selectedVehicleId = $soItem ? $soItem->vehicles_id : null;
                                    @endphp
                                    <select name="vehicle_vin[{{ $quotationItem->id }}][]" class="form-control select2">
                                        <option value="" selected>Select VIN</option>
                                        @foreach($vehicles[$quotationItem->id] as $vehicle)
                                        @php
                                        // Determine if this vehicle should be selected
                                        $selected = ($vehicle->id == $selectedVehicleId) ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $vehicle->id }}" {{ $selected }}>{{ $vehicle->vin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endfor
                        </div>
                        <input type="hidden" name="quotation_item_id[]" value="{{ $quotationItem->id }}">
                    </div>
                    @endforeach

                </div>
            </div>
            <hr>
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
                                <input type="number" class="form-control" id="total_payment" name="total_payment" value="{{$quotation->deal_value}}" readonly>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="receiving_payment"><strong>Total Receiving Payment</strong></label>
                                <input type="number" class="form-control" id="receiving_payment" name="receiving_payment" value="{{$sodetails->receiving}}" readonly>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="advance_payment_performa"><strong>Payment In Performa</strong></label>
                                <input type="number" class="form-control payment" id="advance_payment_performa" name="advance_payment_performa" value="{{$quotation->quotationdetails->advance_amount}}" readonly>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="payment_so"><strong>Payment In SO</strong></label>
                                <input type="number" class="form-control payment" id="payment_so" name="payment_so" value="{{$sodetails->paidinso}}" required>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="balance_payment"><strong>Balance Payment</strong></label>
                                @php
                                $balance = $sodetails->total - $sodetails->receiving;
                                @endphp
                                <input type="number" class="form-control" id="balance_payment" value="{{$balance}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </br>
            </br>
            <input type="hidden" name="so_id" value="{{ $sodetails->id }}">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    @endsection
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script>
        function updateTotalReceivingPayment() {
            var paymentPerforma = parseFloat(document.getElementById('advance_payment_performa').value) || 0;
            var paymentSO = parseFloat(document.getElementById('payment_so').value) || 0;
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
                updateTotalReceivingPayment();
                updateBalancePayment();
            });
        });
    </script>
    <script>
        // JavaScript code to check for duplicate VINs
        function checkForDuplicateVINs() {
            var selectedVINs = {};
            var dropdowns = document.querySelectorAll('select[name^="vehicle_vin"]');

            for (var i = 0; i < dropdowns.length; i++) {
                var selectedOption = dropdowns[i].value;

                if (selectedOption && selectedOption !== '') {
                    if (selectedVINs[selectedOption]) {
                        // Duplicate VIN found, display an error message and prevent form submission
                        alert('Duplicate VIN ' + selectedOption + ' selected. Please select a unique VIN for each vehicle.');
                        return false; // Prevent form submission
                    }
                    selectedVINs[selectedOption] = true;
                }
            }
            return true; // No duplicate VINs found, allow form submission
        }
    </script>
    <script>
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