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
    <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-4">
                        Document Type :
                    </div>
                    <div class="col-sm-6">
                        <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox2">{{$quotation->document_type}} To Sales Order</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-2">
                        Category :
                    </div>
                    <div class="col-sm-4">
                    
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-2">
                        Currency :
                    </div>
                     <div class="col-sm-4">
                     <label class="form-check-label" for="inlineCheckbox2">{{$quotation->currency}}</label>
                </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4">
<strong>Document Details</strong>
            </div>
            <div class="col-sm-4">
            <strong>Client's Details</strong>
            </div>
            <div class="col-sm-4">
            <strong> Delivery Details</strong>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        <label for="timeRange">Document Validity:</label>
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$customerdetails->document_validity}}</label>
                </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Person :
                    </div>
                    <div class="col-sm-6">
                    {{ $saleperson->name}}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Office :
                    </div>
                    <div class="col-sm-6">
                    {{ isset($empProfile->office) ? $empProfile->office : '' }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Email ID :
                    </div>
                    <div class="col-sm-6">
                    {{ $saleperson->email }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Contact No :
                    </div>
                    <div class="col-sm-6">
                    {{ isset($empProfile->phone) ? $empProfile->phone : '' }}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Client Category :
                    </div>
                    <div class="col-sm-6">
                    @if(!$calls->company_name)
                    <label class="form-check-label">Individual</label>
                    @else
                    <label class="form-check-label">Company</label>
                </div>
                </div>
                <div class="row mt-2" id="contact-person-div">
                    <div class="col-sm-6">
                        Contact Person :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$calls->name}}</label>
                    </div>
                </div>
                <div class="row mt-2" id="company-div" >
                    <div class="col-sm-6">
                        Company :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$calls->company_name}}</label>
                    @endif
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        <label for="timeRange">Customer :</label>
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$calls->name}}</label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Contact Number :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$calls->phone}}</label>   
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Email :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$calls->email}}</label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Address :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$calls->address}}</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" >
                <div id="export-shipment">
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Final Destination :
                        </div>
                        <div class="col-sm-6">
                        <label class="form-check-label">
                            @if ($customerdetails->country)
                                {{ $customerdetails->country->name }}
                            @else
                            @endif
                        </label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Incoterm :
                        </div>
                        <div class="col-sm-6">
                        <label class="form-check-label">{{$customerdetails->incoterm}}</label>
                    </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Port of Discharge :
                        </div>
                        <div class="col-sm-6">
                        <label class="form-check-label" for="inlineCheckbox2">
                            @if ($customerdetails->shippingPort)
                            {{$customerdetails->shippingPort->shipping_port_id}}
                            @else
                            @endif
                        </label>
                    </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Port of Loading :
                        </div>
                        <div class="col-sm-6">
                        <label class="form-check-label" for="inlineCheckbox2">
                        @if ($customerdetails->shippingPortOfLoad)
                        {{$customerdetails->shippingPortOfLoad->to_shipping_port_id}}
                        @else
                        @endif
                        </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" hidden id="local-shipment">
                    <div class="col-sm-6">
                        Place of Supply :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$customerdetails->place_of_supply}}</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-2">
            <div class="col-sm-4">
                Payment Details
            </div>
            <div class="col-sm-8">
                Client's Representative
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Payment Terms :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">
                        @if ($customerdetails->paymentterms)
                        {{$customerdetails->paymentterms->name}}
                        @else
                        @endif
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Rep Name :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$customerdetails->representative_name}}</label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Rep No :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$customerdetails->representative_number}}</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        CB Name:
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$customerdetails->cb_name}}</label>
                </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        CB No:
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$customerdetails->cb_number}}</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4"  id="advance-amount-div" hidden>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Advance Amount :
                    </div>
                    <div class="col-sm-6">
                    <label class="form-check-label" for="inlineCheckbox2">{{$customerdetails->advance_amount}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-12">
                <div class="row mt-2">
                    <div class="col-sm-2">
                        Remarks :
                    </div>
                    <div class="col-sm-10">
                    <label class="form-check-label" for="inlineCheckbox2">{{$quotation->sales_notes}}</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
            <div class="row">
            <h6>SO Details</h6>
        <div class="col-md-2 mb-3">
            <label for="today_date">SO Date</label>
            <input type="date" class="form-control" id="so_date" name="so_date" value="{{$sodetails->so_date}}">
        </div>
        <div class="col-md-2 mb-3">
            <label for="text_input">Netsuit SO Number</label>
            <div class="input-wrapper">
            <span class="prefix">SO-00</span>
            <input type="text" class="form-control input-field" id="so_number" name="so_number" placeholder="Enter SO Number" value="{{$sodetails->so_number}}">
        </div>
        <div id="error_message" class="error-message"></div>
        </div>
        <div class="col-md-8 mb-3">
            <label for="text_area">Sales Notes</label>
            <textarea class="form-control" id="notes" name="notes" value="{{$sodetails->notes}}"></textarea>
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
        <div class="col-md-2 mb-3">
            @php
                // Get the corresponding so_item for this specific loop iteration
                $soItem = $soitems->where('quotation_items_id', $quotationItem->id)->skip($i)->first();
                $soItemId = $soItem ? $soItem->id : null;
                $selectedVehicleId = $soItem ? $soItem->vehicles_id : null;
            @endphp
            <select name="vehicle_vin[{{ $quotationItem->id }}][]" id="soitem_{{ $soItemId }}" class="form-control select2">
                <option value="" selected>Select VIN</option>
                @foreach($vehicles[$quotationItem->id] as $vehicle)
                    @if($vehicle->inspection_status != "Pending")
                        @php
                            // Determine if this vehicle should be selected
                            $selected = ($vehicle->id == $selectedVehicleId) ? 'selected' : '';
                        @endphp
                        <option value="{{ $vehicle->id }}" {{ $selected }}>{{ $vehicle->vin }}</option>
                    @endif
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
        <h6>Payments</h6>
        <div class="row">
    <div class="col-md-12">
        <div class="col-md-2 mb-3">
            <label for="total_payment">Currency</label>
            <input type="text" class="form-control" id="currency" name="currency" value="{{$quotation->currency}}" readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 mb-3">
            <label for="total_payment">Total Payment</label>
            <input type="number" class="form-control" id="total_payment" name="total_payment" value="{{$quotation->deal_value}}" readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 mb-3">
                <label for="receiving_payment">Total Receiving Payment</label>
                <input type="number" class="form-control" id="receiving_payment" name="receiving_payment" value="{{$sodetails->receiving}}" readonly>
            </div>
            <div class="col-md-2 mb-3">
                <label for="advance_payment_performa">Payment In Performa</label>
                <input type="number" class="form-control payment" id="advance_payment_performa" name="advance_payment_performa" value="{{$quotation->quotationdetails->advance_amount}}" readonly>
            </div>
            <div class="col-md-2 mb-3">
                <label for="payment_so">Payment In SO</label>
                <input type="number" class="form-control payment" id="payment_so" name="payment_so" value="{{$sodetails->paidinso}}" required>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 mb-3">
            <label for="balance_payment">Balance Payment</label>
            @php
            $balance = $sodetails->total - $sodetails->receiving;
            @endphp
            <input type="number" class="form-control" id="balance_payment" value="{{$balance}}" readonly>
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

        soInput.addEventListener('input', function () {
            const regex = /^[1-9][0-9]{3,20}$/; // Starts with 1-9, followed by 3-7 digits
            const value = soInput.value;

            if (!regex.test(value)) {
                errorMessage.textContent = "SO Number must be 4-20 digits and cannot start with zero or include alphabets.";
                soInput.setCustomValidity("Invalid");
            } else {
                errorMessage.textContent = "";
                soInput.setCustomValidity("");
            }
        });
    </script>
@endpush