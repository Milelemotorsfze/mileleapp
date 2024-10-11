@extends('layouts.main')
@section('content')
<style>
        /* Add any additional styling here */
        .hidden {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">Create New Invoice</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
    <form action="{{ route('vehicleinvoice.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
    <div class="col-lg-2 col-md-6">
            <label for="from_port" class="form-label">Invoice Number</label>
            <input type="text" name="invoice_number" class="form-control" required/>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="from_port" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::today()->toDateString() }}" />
        </div>
        <div class="col-lg-2 col-md-6">
    <label for="currency" class="form-label">Currency</label>
    <select name="currency" class="form-control" id="currency">
        <option value="USD">USD</option>
        <option value="AED">AED</option>
    </select>
</div>
        <div class="col-lg-2 col-md-6">
            <label for="so" class="form-label">Sales Order</label>
            <select name="so" class="form-control" id="so" onchange="loadTableData(this.value)">
                <option value="" disabled selected>Select Sales Order</option>
                @foreach($so as $sos)
                    <option value="{{ $sos->id }}">{{ $sos->so_number }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <br><br>
    <p id="no-vehicles-message" style="display: none; color: red;">No vehicles remaining for invoice.</p>
    <div class="row" id="vehicle-details" style="display:none;">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Gross Amount</th>
                    </tr>
                </thead>
                <tbody id="vehicle-rows">
                    <!-- Dynamic rows will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <br><br>

    <!-- Additional fields and Submit button -->
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <label for="discount" class="form-label">Discount</label>
            <input type="number" name="discount" class="form-control" id="discount" step="0.01" value="0" />
        </div>
        <div class="col-lg-4 col-md-6">
            <label for="vat" class="form-label">VAT (%)</label>
            <input type="number" name="vat" class="form-control" id="vat" step="0.01" value="0" />
        </div>
        <div class="col-lg-4 col-md-6">
            <label for="shipping_charges" class="form-label">Shipping Charges</label>
            <input type="number" name="shipping_charges" class="form-control" id="shipping_charges" step="0.01" value="0" />
        </div>
    </div>

    <br><br>

    <div class="col-lg-12 col-md-12">
        <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter">
    </div>
</form>
    </div>
    <br>
</div>
@endsection
@push('scripts')
<script>
    function loadTableData(soId) {
        if (soId) {
            // Make an AJAX call to fetch the vehicle data based on the SO ID
            $.ajax({
                url: '{{ route('getVehiclesBySO') }}',
                method: 'POST',
                data: {
                    so_id: soId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    const vehicleRows = document.getElementById('vehicle-rows');
                    vehicleRows.innerHTML = ''; // Clear previous rows

                    // Check if vehicles are returned
                    if (response.length > 0) {
                        response.forEach((vehicle, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${vehicle.brand_name} ${vehicle.model_detail} ${vehicle.my} </br> ${vehicle.vin}</td>
                                <td><input type="hidden" name="vehicle_id[]" value="${vehicle.id}" />
                                <td><input type="number" name="qty[]" class="form-control" value="1" min="1" onchange="calculateGross(this)" /></td>
                                <td><input type="number" name="unit_price[]" class="form-control" value="${vehicle.price}" step="0.01" onchange="calculateGross(this)" /></td>
                                <td><input type="text" name="gross_amount[]" class="form-control" readonly value="${vehicle.price}" /></td>
                            `;
                            vehicleRows.appendChild(row);
                        });
                        // Show the table
                        document.getElementById('vehicle-details').style.display = 'block';
                        document.getElementById('no-vehicles-message').style.display = 'none';
                    } else {
                        // Hide the table if no vehicles are found
                        document.getElementById('vehicle-details').style.display = 'none';
                        document.getElementById('no-vehicles-message').style.display = 'block';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching vehicle data:', error);
                }
            });
        } else {
            // Hide the table if no SO is selected
            document.getElementById('vehicle-details').style.display = 'none';
        }
    }
    function calculateGross(input) {
        const row = input.closest('tr');
        const qty = row.querySelector('input[name="qty[]"]').value;
        const unitPrice = row.querySelector('input[name="unit_price[]"]').value;
        const grossAmount = row.querySelector('input[name="gross_amount[]"]');
        grossAmount.value = (qty * unitPrice).toFixed(2);
    }
</script>
<script>
        $(document).ready(function() {
            $('#so').select2();
        });
    </script>
@endpush