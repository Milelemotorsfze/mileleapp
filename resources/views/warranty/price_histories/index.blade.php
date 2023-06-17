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
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#reject-selling-price-{{$pendingSellingPriceHistory->id}}" data-status="reject">
                                            Reject
                                        </button>
                                    </td>
                                    <div class="modal fade" id="reject-selling-price-{{$pendingSellingPriceHistory->id}}"
                                         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Selling Price Rejection</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-center">Current Price</label>
                                                                    </div>
                                                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{  $pendingSellingPriceHistory->warrantyBrand->price }}"
                                                                               class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-muted">New Price</label>
                                                                    </div>
                                                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{ $pendingSellingPriceHistory->selling_price }}" class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary  status-reject-button">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
    <script type="text/javascript">
        $(document).ready(function () {
            $('.status-reject-button').click(function (e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                statusChange(id,status)
            })
            function statusChange(id,status) {

                let url = '{{ route('warranty-brands.approve-selling-price') }}';
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        id: id,
                        status: status,
                        review:review,
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        window.location.reload();
                        alertify.success(status +" Successfully")
                    }
                });
            }
        })
    </script>
@endpush
