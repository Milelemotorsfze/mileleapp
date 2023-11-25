@extends('layouts.table')
<style>
    .scrollable-card {
            max-height: 350px; /* Set your desired fixed height here */
            overflow-y: auto; /* Add a scrollbar when content exceeds the height */
        }
</style>
@section('content')
<div class="card-header">
    <h4 class="card-title">Lead ID : {{$calls->id}}</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="row p-3">
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
    <div class="card" style="min-height: 350px;">
        <div class="card-header align-items-center bg-primary text-white">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3 text-white">Prospecting</h4>
        </div>
        <div class="card-body scrollable-card">
            <ul class="list-group">
                @foreach($prospecting as $prospect)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0">{{$prospect->salesnotes}}</p>
                            <small class="text-muted">Date: {{ date('M j, Y', strtotime($prospect->date)) }}</small>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
    <div class="card" style="min-height: 350px;">
        <div class="card-header align-items-center bg-primary text-white">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3 text-white">Demand</h4>
        </div>
        <div class="card-body scrollable-card">
            <ul class="list-group">
                @foreach($demands as $demand)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0">{{$demand->salesnotes}}</p>
                            <small class="text-muted">Date: {{ date('M j, Y', strtotime($demand->date)) }}</small>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
    <div class="card" style="min-height: 350px;">
        <div class="card-header align-items-center bg-primary text-white">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3 text-white">Quotation</h4>
        </div>
        <div class="card-body scrollable-card">
            <ul class="list-group">
                @foreach($quotations as $quotation)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0">{{$quotation->sales_notes}}</p>
                            <small class="text-muted">Deal Value: {{$quotation->deal_value}}</small>
                            <br>
                            <small class="text-muted">Date: {{ date('M j, Y', strtotime($quotation->date)) }}</small>
                        </div>
                        @if($quotation->file_path)
                            <a href="javascript:void(0);" onclick="openModalFile('{{$quotation->file_path}}', 'fileModal{{$quotation->id}}', 'fileViewer{{$quotation->id}}')">
                                <i class="fas fa-file"></i> View File
                            </a>
                        @endif
                    </li>
                    <div class="modal fade" id="fileModal{{$quotation->id}}" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel{{$quotation->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="fileModalLabel{{$quotation->id}}">Quotation File Viewer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe id="fileViewer{{$quotation->id}}" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
    <div class="card" style="min-height: 350px;">
        <div class="card-header align-items-center bg-primary text-white">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3 text-white">Negotiation</h4>
        </div>
        <div class="card-body scrollable-card">
            <ul class="list-group">
                @foreach($negotiations as $negotiations)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0">{{$negotiations->sales_notes}}</p>
                            <small class="text-muted">Deal Value: {{$negotiations->deal_value}}</small>
                            <br>
                            <small class="text-muted">Date: {{ date('M j, Y', strtotime($negotiations->date)) }}</small>
                        </div>
                        @if($negotiations->file_path)
                            <a href="javascript:void(0);" onclick="openModalFilen('{{$negotiations->file_path}}', 'nfileModal{{$negotiations->id}}', 'nfileViewer{{$negotiations->id}}')">
                                <i class="fas fa-file"></i> View File
                            </a>
                        @endif
                    </li>
                    <div class="modal fade" id="nfileModal{{$negotiations->id}}" tabindex="-1" role="dialog" aria-labelledby="nfileModalLabel{{$negotiations->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="nfileModalLabel{{$negotiations->id}}">Negotiation File Viewer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe id="nfileViewer{{$negotiations->id}}" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
    <div class="card" style="min-height: 350px;">
        <div class="card-header align-items-center bg-primary text-white">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3 text-white">Sales Order</h4>
        </div>
        <div class="card-body scrollable-card">
            <ul class="list-group">
                @foreach($closed as $closeds)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                        @php
                $so = \App\Models\So::find($closeds->so_id);
            @endphp

            @if($so)
                <p>SO Number: {{ $so->so_number }}</p>
            @else
                <p>SO Number not found</p>
            @endif
                            <p class="mb-0">{{$closeds->salesnotes}}</p>
                            <small class="text-muted">Deal Value: {{$closeds->deal_value}} - {{$closeds->currency}}</small>
                            <br>
                            <small class="text-muted">Date: {{ date('M j, Y', strtotime($closeds->date)) }}</small>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
    <div class="card" style="min-height: 350px;">
        <div class="card-header align-items-center bg-primary text-white">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3 text-white">Booking</h4>
        </div>
        <div class="card-body scrollable-card">
        <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                    <tr>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Variant</th>
                        <th>VIN</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookingDetails as $booking)
                        <tr>
                            <td>{{ $booking->brand_name }}</td>
                            <td>{{ $booking->model_line }}</td>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $booking->vin }}</td>
                            <td>{{ date('M j, Y', strtotime($booking->booking_start_date)) }}</td>
                            <td>{{ date('M j, Y', strtotime($booking->booking_end_date)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
</div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function openModalFile(filePath, modalId, viewerId) {
        const baseUrl = "{{ asset('storage/') }}";
        const fileUrl = baseUrl + '/' + filePath;
        $('#' + viewerId).attr('src', fileUrl);
        $('#' + modalId).modal('show');
    }
    function openModalFilen(filePath, nmodalId, nviewerId) {
        const baseUrl = "{{ asset('storage/') }}";
        const fileUrl = baseUrl + '/' + filePath;
        $('#' + nviewerId).attr('src', fileUrl);
        $('#' + nmodalId).modal('show');
    }
</script>
@endpush