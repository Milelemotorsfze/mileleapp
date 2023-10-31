@extends('layouts.table')
@section('content')
@canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">
		@if($type == 'P') Accessories @elseif($type == 'SP') Spare Parts @elseif($type == 'K') Kits @endif
		Selling Price For Approval
	</h4>
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
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
        @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-selling-prices">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-selling-prices">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-selling-prices">Rejected </a>
		</li>
        @endif
        @endcanany
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
    @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
    @endphp
    @if ($hasPermission)
	<div class="tab-pane fade show active" id="pending-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>No</th>
							<th>Addon code</th>
							<th>Addon Name</th>
							<th>Current Price (AED)</th>
							<th>Requested Price (AED)</th>
							<th>Created By</th>
							<th>Updated By</th>
							<th>Date & Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $pending)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $pending->addonDetail->addon_code ?? '' }}</td>
							<td>{{ $pending->addonDetail->AddonName->name ?? '' }} 
								@if($pending->addonDetail->addon_type_name == 'P' OR $pending->addonDetail->addon_type_name == 'SP') 
								@if($pending->addonDetail->AddonDescription->name != '')- {{ $pending->addonDetail->AddonDescription->name ?? '' }}@endif
                                @endif
							</td>
							<td>{{ $pending->currentPrice ?? '' }}</td>
							<td>{{ $pending->selling_price ?? '' }}</td>
							<td>{{ $pending->CreatedBy->name ?? '' }}</td>
							<td>{{ $pending->UpdatedBy->name ?? '' }}</td>
							<td>{{ $pending->updated_at ?? ''}}</td>
							<td>
                                @canany(['edit-addon-new-selling-price'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price']);
                                @endphp
                                @if ($hasPermission)
								<button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal"
									data-bs-target="#edit-selling-price-{{$pending->id}}">
								<i class="fa fa-edit"></i></button>
                                @endif
                                @endcanany
                                @canany(['approve-addon-new-selling-price'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['approve-addon-new-selling-price']);
                                @endphp
                                @if ($hasPermission)
								<button type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-selling-price-{{$pending->id}}">
								Approve
								</button>
                                @endif
                                @endcanany
                                @canany(['reject-addon-new-selling-price'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['reject-addon-new-selling-price']);
                                @endphp
                                @if ($hasPermission)
								<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-selling-price-{{$pending->id}}">
								Reject
								</button>
                                @endif
                                @endcanany
								@can('addon-view')
								@php
									$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-view']);
								@endphp
								@if ($hasPermission)
									@if($pending->addonDetail->addon_type_name == 'K')
										<a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('kit.kitItems',$pending->addonDetail->id) }}">
												<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
									@else
										<a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('addon.kitItems',$pending->addonDetail->id) }}">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
									@endif
								@endif
							@endcan
							</td>
							<div class="modal fade" id="edit-selling-price-{{$pending->id}}"  tabindex="-1"
								aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $pending->id) }}"
										method="POST" >
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
																	<input name="selling_price" id="update_selling_price_{{$pending->id}}"
																		oninput="inputNumberAbs(this)" class="form-control" required
																		placeholder="Enter Selling Price" value="{{$pending->selling_price}}">
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
							<div class="modal fade" id="approve-selling-price-{{$pending->id}}"
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
																<input type="text" value="{{ $pending->currentPrice ?? '' }}" class="form-control" readonly>
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-3 col-md-12 col-sm-12">
																<label class="form-label font-size-13">New Price</label>
															</div>
															<div class="col-lg-9 col-md-12 col-sm-12">
																<input type="text" value="{{ $pending->selling_price ?? '' }}"
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
												data-id="{{ $pending->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="reject-selling-price-{{$pending->id}}"
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
																<input type="text" value="{{ $pending->currentPrice ?? '' }}"
																	class="form-control" readonly >
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-3 col-md-12 col-sm-12">
																<label class="form-label font-size-13">New Price</label>
															</div>
															<div class="col-lg-9 col-md-12 col-sm-12">
																<input type="text" value="{{ $pending->selling_price }}"
																	id="updated-price"  class="form-control" readonly >
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary  status-reject-button" data-id="{{ $pending->id }}"
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
                            <th>Addon code</th>
							<th>Addon Name</th>
							<th>Approved Price(AED)</th>
                            <th>Requested By</th>
							<th>Updated By</th>
							<th>Approved By</th>
							<th>Approved Date & Time</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $approvedOne)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
                            <td>{{ $approvedOne->addonDetail->addon_code ?? '' }}</td>
							<td>{{ $approvedOne->addonDetail->AddonName->name ?? '' }} 
								@if($approvedOne->addonDetail->addon_type_name == 'P' OR $approvedOne->addonDetail->addon_type_name == 'SP') 
                                @if(isset($approvedOne->addonDetail->AddonDescription->name))
								@if($approvedOne->addonDetail->AddonDescription->name != '')- {{ $approvedOne->addonDetail->AddonDescription->name ?? '' }}@endif
                                @endif
                                @endif
							</td>
							<td>{{ $approvedOne->selling_price ?? '' }}</td>
							<td>{{ $approvedOne->CreatedBy->name ?? '' }}</td>
							<td>{{ $approvedOne->UpdatedBy->name ?? '' }}</td>
                            <td>{{ $approvedOne->StatusUpdatedBy->name ?? '' }}</td>
							<td>{{ $approvedOne->updated_at}}</td>
							<td>{{ $approvedOne->status}}</td>
							<td>
								@if ($hasPermission)
									@if( $approvedOne->addonDetail->addon_type_name == 'K')
										<a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('kit.kitItems', $approvedOne->addonDetail->id) }}">
												<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
									@else
										<a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('addon.kitItems', $approvedOne->addonDetail->id) }}">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
									@endif
								@endif
							</td>
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
							<th>Addon code</th>
							<th>Addon Name</th>
							<th>Requested Price(AED)</th>
                            <th>Requested By</th>
							<th>Updated By</th>
							<th>Rejected By</th>
							<th>Rejected Date & Time</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $rejectedOne)
						<tr data-id="1">
                            <td>{{ ++$i }}</td>
                            <td>{{ $rejectedOne->addonDetail->addon_code ?? '' }}</td>
							<td>{{ $rejectedOne->addonDetail->AddonName->name ?? '' }} 
								@if($rejectedOne->addonDetail->addon_type_name == 'P' OR $rejectedOne->addonDetail->addon_type_name == 'SP') 
                                @if(isset($rejectedOne->addonDetail->AddonDescription->name))
								@if($rejectedOne->addonDetail->AddonDescription->name != '')- {{ $rejectedOne->addonDetail->AddonDescription->name ?? '' }}@endif
                                @endif
                                @endif
							</td>
							<td>{{ $rejectedOne->selling_price ?? '' }}</td>
							<td>{{ $rejectedOne->CreatedBy->name ?? '' }}</td>
							<td>{{ $rejectedOne->UpdatedBy->name ?? '' }}</td>
                            <td>{{ $rejectedOne->StatusUpdatedBy->name ?? '' }}</td>
							<td>{{ $rejectedOne->updated_at}}</td>
							<td>
								@if ($hasPermission)
									@if( $rejectedOne->addonDetail->addon_type_name == 'K')
										<a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('kit.kitItems', $rejectedOne->addonDetail->id) }}">
												<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
									@else
										<a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('addon.kitItems', $rejectedOne->addonDetail->id) }}">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
									@endif
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
    @endif
    @endcanany
</div>
@endif
@endcanany
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
	        let url = '{{ route('addon-selling-price.status-change') }}';
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
	function inputNumberAbs(currentPriceInput) 
	{
	    var id = currentPriceInput.id;
	    var input = document.getElementById(id);
	    var val = input.value;
	    val = val.replace(/^0+|[^\d.]/g, '');
	    if(val.split('.').length>2) 
	    {
	        val =val.replace(/\.+$/,"");
	    }
	    input.value = val;
	}
</script>
@endpush