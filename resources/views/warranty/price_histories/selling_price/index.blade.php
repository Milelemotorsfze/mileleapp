@extends('layouts.table')
@section('content')
        <div class="card-header">
            <h4 class="card-title">Selling Price Histories</h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger" >
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
        </div>
        @can('warranty-selling-price-histories-list')
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-selling-price-histories-list']);
        @endphp
        @if ($hasPermission)
        <div class="portfolio">
            <ul class="nav nav-pills nav-fill" id="my-tab">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#pending-selling-prices">Pending </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#approved-selling-prices">Approved </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#rejected-selling-prices">Rejected </a>
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
                                <th>Old Price (AED)</th>
                                <th>Requested Price (AED)</th>
                                <th>Created By</th>
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
                                    <td>{{ $pendingSellingPriceHistory->old_price ?? '' }}</td>
                                    <td>{{ $pendingSellingPriceHistory->updated_price ?? '' }}</td>
                                    <td>{{ $pendingSellingPriceHistory->createdUser->name ?? '' }}</td>
                                    <td>{{ $pendingSellingPriceHistory->updatedUser->name ?? '' }} </td>
                                    <td>{{ $pendingSellingPriceHistory->updated_at }} </td>
                                    <td>
                                        @can('warranty-selling-price-histories-edit')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-selling-price-histories-edit']);
                                        @endphp
                                        @if ($hasPermission)
                                            <button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal"
                                                    data-bs-target="#edit-selling-price-{{$pendingSellingPriceHistory->id}}">
                                                <i class="fa fa-edit"></i></button>
                                        @endif
                                        @endcan
                                        @can('warranty-selling-price-approve')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-selling-price-approve']);
                                        @endphp
                                        @if ($hasPermission)
                                            <button type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
                                                    data-bs-target="#approve-selling-price-{{$pendingSellingPriceHistory->id}}">
                                                Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#reject-selling-price-{{$pendingSellingPriceHistory->id}}">
                                                Reject
                                            </button>
                                        @endif
                                        @endcan
                                    </td>
                                    <div class="modal fade" id="edit-selling-price-{{$pendingSellingPriceHistory->id}}"  tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <form id="form-update" action="{{ route('warranty-price-histories.update', $pendingSellingPriceHistory->id) }}"
                                                  method="POST" >
                                                @method('PUT')
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Selling Price</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-muted">Selling Price</label>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="input-group">
                                                                            <input type="number" name="selling_price" class="form-control"
                                                                                   placeholder="Enter Selling Price" value="{{$pendingSellingPriceHistory->updated_price}}"
                                                                                  step="any" min="0">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary ">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="approve-selling-price-{{$pendingSellingPriceHistory->id}}"
                                         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Selling Price Approval</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-center">Current Price</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{  $pendingSellingPriceHistory->warrantyBrand->selling_price }}"
                                                                               class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13">New Price</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{ $pendingSellingPriceHistory->updated_price }}"
                                                                               id="updated-price"  class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary status-approve-button"
                                                            data-id="{{ $pendingSellingPriceHistory->id }}" data-status="approved">Approve</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-center">Current Price</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{  $pendingSellingPriceHistory->warrantyBrand->selling_price }}"
                                                                               class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13">New Price</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{ $pendingSellingPriceHistory->updated_price }}"
                                                                               id="updated-price"  class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary  status-reject-button" data-id="{{ $pendingSellingPriceHistory->id }}"
                                                            data-status="rejected">Reject</button>
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
                                    <td>{{ $rejectedSellingPriceHistory->statusUpdatedUser->name ?? '' }} </td>
                                    <td>{{ $rejectedSellingPriceHistory->updated_at }} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
       @endif
       @endcan
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.status-reject-button').click(function (e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                updateSellingPrice(id, status)
            })
            $('.status-approve-button').click(function (e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                updateSellingPrice(id, status)
            })
            function updateSellingPrice(id, status) {

                var updated_price = $('#updated-price').val();
                let url = '{{ route('warranty-brands.update-selling-price') }}';
                if(status == 'rejected') {
                    var message = 'Reject';
                }else{
                    var message = 'Approve';
                }
                var confirm = alertify.confirm('Are you sure you want to '+ message +' this item ?',function (e) {
                    if (e) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: {
                                id: id,
                                status: status,
                                updated_price: updated_price,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (data) {
                                window.location.reload();
                                alertify.success(status + " Successfully")
                            }
                        });
                    }

                }).set({title:"Update Status"})
            }
        })
    </script>
@endpush
