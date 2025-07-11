@extends('layouts.table')

@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Client Detail
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a class="btn btn-sm btn-primary float-end" href="{{ route('clienttransitions.clienttransitions', ['client_id' => $client->id]) }}" style="text-align: right;">
    <i class="fa fa-money" aria-hidden="true"></i> Accounts & Transitions
</a>
        </h4>
        <br>
    </div>
    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Quotation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be dynamically loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <div class="card-body">
        <div class="mb-4">
    <h5>Client Information</h5>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="fw-bold">Name:</label>
                <p>{{ $client->name }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Phone:</label>
                <p>{{ $client->phone }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Email:</label>
                <p>{{ $client->email }}</p>
            </div>
            <div class="mb-3">
        <label class="fw-bold">Deposit:</label>
        <p>
            <i class="bi bi-passport icon-clickable" data-toggle="modal" data-target="#passportModal"></i>
        </p>
    </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="fw-bold">Source:</label>
                <p>{{ $client->source }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Language:</label>
                <p>{{ $client->language }}</p>
            </div>
            <div class="mb-3">
                <label class="fw-bold">Client Type:</label>
                <p>{{ $client->type }}</p>
            </div>
            @if ($client->company_name)
                <div class="mb-3">
                    <label class="fw-bold">Company Name:</label>
                    <p>{{ $client->company_name }}</p>
                </div>
            @endif
            <div class="mb-3">
        <label class="fw-bold">Credit Limit:</label>
        <p>
            <i class="bi bi-passport icon-clickable" data-toggle="modal" data-target="#passportModal"></i>
        </p>
    </div>
    <div class="mb-3">
        <label class="fw-bold">Credit:</label>
        <p>
            <i class="bi bi-passport icon-clickable" data-toggle="modal" data-target="#passportModal"></i>
        </p>
    </div>
            @if ($client->passport)
    <div class="mb-3">
        <label class="fw-bold">Passport:</label>
        <p>
            <i class="bi bi-passport icon-clickable" data-toggle="modal" data-target="#passportModal"></i>
        </p>
    </div>
@endif

@if ($client->tender)
    <div class="mb-3">
        <label class="fw-bold">Tender:</label>
        <p>
            <i class="bi bi-file-earmark-text icon-clickable" data-toggle="modal" data-target="#tenderModal"></i>
        </p>
    </div>
@endif

@if ($client->tradelicense)
    <div class="mb-3">
        <label class="fw-bold">Trade License:</label>
        <p>
            <i class="bi bi-file-earmark-text icon-clickable" data-toggle="modal" data-target="#tradeLicenseModal"></i>
        </p>
    </div>
@endif
        </div>
    </div>
</div>
<hr>
<div class="mt-4">
    <!-- Quotation Issues Section -->
    <div class="mb-4">
    <h5>Leads</h5>
    <div class="table-responsive" >
                    <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                <th>Lead ID</th>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Brand - Model</th>
                    <th>Custom Model & Brand</th>
                    <th>Shipping Type</th>
                    <th>Country Of Export</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    </div>
    <hr>
    <!-- Sales Orders Section -->
    <div class="mb-4">
        <h5>Quotations</h5>
        <div class="table-responsive" >
                    <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                <th>Qoutation ID</th>
                <th>Date</th>
                    <th>Category</th>
                    <th>Final Destination</th>
                    <th>Incoterm</th>
                    <th>Port Of Discharge</th>
                    <th>No of Vehicles</th>
                    <th>Currency</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<hr>
   <!-- Sales Orders Section -->
   <div class="mb-4">
        <h5>Booking</h5>
        <div class="table-responsive" >
                    <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>Request Date</th>
                    <th>VIN</th>
                    <th>Brand</th>
                    <th>Model Line</th>
                    <th>Model Detail</th>
                    <th>Variant</th>
                    <th>Interior Colour</th>
                    <th>Exterior Colour</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Days</th>
                    <th>ETD</th>
                    <th>Booking Notes</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<hr>
    <!-- Leads Section -->
    <div class="mb-4">
        <h5>Sales Orders</h5>
        <div class="table-responsive" >
                    <table id="dtBasicExample4" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                <th>Category</th>
                    <th>Final Destination</th>
                    <th>Incoterm</th>
                    <th>Port Of Discharge</th>
                    <th>No of Vehicles</th>
                    <th>Currency</th>
                    <th>Total Price</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
    </div>
    </div>
</div>
</div>
@endsection
@push('scripts')
<script>
    const BASE_URL = "{{ url('/') }}/storage/";
    $(document).ready(function() {
        $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('salescustomers.viewleads', ['clientId' => $client->id]) !!}',
            columns: [
                { data: 'id', name: 'calls.id' },
                { data: 'formatted_created_at', name: 'calls.created_at' },
                { data: 'source_name', name: 'lead_source.source_name' },
                { data: 'brand_model_lines', name: 'brand_model_lines', searchable: false },
                { data: 'custom_brand_model', name: 'calls.custom_brand_model' },
                { data: 'type', name: 'calls.type' },
                { data: 'countryofexport', name: 'calls.countryofexport' },
                { data: 'status', name: 'calls.status' },
                { data: 'remarks', name: 'calls.remarks' },
            ]
        });
        $('#dtBasicExample2').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route('salescustomers.qoutationview', ['clientId' => $client->id]) !!}',
    columns: [
        { data: 'id', name: 'quotations.id' },
        { data: 'formatted_date', name: 'quotations.date'},
        {
            data: 'shipping_method',
            name: 'quotations.shipping_method',
            render: function(data, type, row) {
                if (data === 'CNF') {
                    return 'Local';
                } else {
                    return 'Export';
                }
            }
        },
        {
            data: 'name',
            name: 'countries.name',
            render: function(data, type, row) {
                return data !== null ? data : 'N/A';
            }
        },
        {
            data: 'incoterm',
            name: 'quotation_details.incoterm',
            render: function(data, type, row) {
                return data !== null ? data : 'N/A';
            }
        }, 
        { data: 'place_of_supply', name: 'quotation_details.place_of_supply' },
        { data: 'quotation_items_count', name: 'quotation_items_count' },
        { data: 'currency', name: 'quotations.currency' },
        { data: 'deal_value', name: 'quotations.deal_value' },
        { data: 'place_of_supply', name: 'quotation_details.place_of_supply' },
        {
            data: 'file_path',
            render: function (data) {
                const fullUrl = BASE_URL + data;
                return `
                    <button class="btn btn-info view-file" data-file-path="${fullUrl}" data-toggle="modal" data-target="#fileModal">
                        <i class="fas fa-eye"></i>
                    </button>
                `;
            }
        }
    ]
});
    });
$('#dtBasicExample2').on('click', '.view-file', function () {
    var filePath = $(this).data('file-path');
    $('#fileModal').find('.modal-body').html('<iframe src="'+filePath+'" width="100%" height="500"></iframe>');
    $('#fileModal').modal('show');
});

</script>
@endpush