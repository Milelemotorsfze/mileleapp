@extends('layouts.table')
@section('content')
    @can('warranty-list')
        <div class="card-header">
            <h4 class="card-title"> Price Histories</h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
        <div class="portfolio">
            <ul class="nav nav-pills nav-fill" id="my-tab">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#purchase-price-histories">Purchase Price Histories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#selling-price-histories">Selling Price Histories</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="purchase-price-histories">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="purchase-price-histories-table" class="table table-striped table-editable table-edits table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Old Price</th>
                                <th>Updated Price</th>
                                <th>Updated By</th>
                                <th>Date & Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}</div>
                            @foreach ($priceHistories as $key => $priceHistory)
                                <tr data-id="1">
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $priceHistory->old_price }}</td>
                                    <td>{{ $priceHistory->updated_price }}</td>
                                    <td>{{ $priceHistory->user->name }} </td>
                                    <td>{{ $priceHistory->updated_at }} </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show " id="selling-price-histories">
                <div class="card-header">
                    <h2 class="card-title"></h2>
                </div>
                <div class="portfolio">
                    <ul class="nav nav-pills nav-fill" id="my-tab">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="pill" href="#pending-selling-prices">Pending Selling Prices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#approved-selling-prices">Approved Selling Prices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#rejected-selling-prices">Rejected Selling Prices</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="selling-price-histories" >
                    <div class="tab-pane fade show active" id="pending-selling-prices">
                        <div class="card-body">
                            <div class="table-responsive">
                        <table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Old Price</th>
                                <th>Requested Price</th>
                                <th>Updated By</th>
                                <th>Date & Time</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}</div>
                            @foreach ($pendingSellingPriceHistories as $key => $pendingSellingPriceHistory)
                                <tr data-id="1">
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $pendingSellingPriceHistory->old_price }}</td>
                                    <td>{{ $pendingSellingPriceHistory->updated_price }}</td>
                                    <td>{{ $pendingSellingPriceHistory->updatedUser->name }} </td>
                                    <td>{{ $pendingSellingPriceHistory->updated_at }} </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm">
                                            Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            Reject
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="approved-selling-prices">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Old Price</th>
                                        <th>Updated Price</th>
                                        <th>Updated By</th>
                                        <th>Approved By</th>
                                        <th>Date & Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <div hidden>{{$i=0;}}</div>
                                    @foreach ($approvedSellingPriceHistories as $key => $approvedSellingPriceHistory)
                                        <tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $approvedSellingPriceHistory->old_price }}</td>
                                            <td>{{ $approvedSellingPriceHistory->updated_price }}</td>
                                            <td>{{ $approvedSellingPriceHistory->updatedUser->name ?? ''}} </td>
                                            <td>{{ $approvedSellingPriceHistory->statusUpdatedUser->name ?? '' }} </td>
                                            <td>{{ $approvedSellingPriceHistory->updated_at }} </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="rejected-selling-prices">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Old Price</th>
                                        <th>Requested Price</th>
                                        <th>Updated By</th>
                                        <th>Rejected By</th>
                                        <th>Date & Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <div hidden>{{$i=0;}}</div>
                                    @foreach ($rejectedSellingPriceHistories as $key => $rejectedSellingPriceHistory)
                                        <tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $rejectedSellingPriceHistory->old_price }}</td>
                                            <td>{{ $rejectedSellingPriceHistory->updated_price }}</td>
                                            <td>{{ $rejectedSellingPriceHistory->updatedUser->name ?? ''}} </td>
                                            <td>{{ $approvedSellingPriceHistory->statusUpdatedUser->name ?? '' }} </td>
                                            <td>{{ $rejectedSellingPriceHistory->updated_at }} </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
@push('scripts')
    <script>

    </script>
@endpush
