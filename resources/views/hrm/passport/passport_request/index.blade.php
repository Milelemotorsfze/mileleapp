@extends('layouts.table')
@section('content')
@canany(['create-passport-request','current-user-create-passport-request','edit-passport-request','current-user-edit-passport-request','view-passport-request-list','current-user-view-passport-request-details'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-passport-request','current-user-create-passport-request','edit-passport-request','current-user-edit-passport-request','view-passport-request-list','current-user-view-passport-request-details']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Passport Submit Info
	</h4>	
	@canany(['create-passport-request','current-user-create-passport-request'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-passport-request','current-user-create-passport-request']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('employee-passport_request.create-or-edit','new') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Passport Request
    </a>
	@endif
	@endcanany
	
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
       
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-hiring-requests">Pending</a>
		</li>
		
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-hiring-requests">Approved</a>
		</li>
		
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-hiring-requests">Rejected</a>
		</li>
        
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>							
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
								@canany(['view-passport-request-details','current-user-view-passport-request-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-details','current-user-view-passport-request-details']);
								@endphp
								@if ($hasPermission)   
                                    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>
								@endif
								@endcanany
								@canany(['edit-passport-request','current-user-edit-passport-request'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-passport-request','current-user-edit-passport-request']);
								@endphp
								@if ($hasPermission)   
								<li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('passport_request.edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
										</a>
									</li>
								@endif
								@endcanany
                                    
                                    <li>
										@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
												<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
													data-bs-target="#approve-passport-submit-request-{{$data->id}}">
													<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
												</button>
												<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
													data-bs-target="#reject-passport-submit-request-{{$data->id}}">
													<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
												</button>
											@endif
										@endif
									</li>
                                </ul>
                            </div>
							</td>
							@include('hrm.passport.passport_request.approve_reject_modal')					
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
    <div class="tab-pane fade show" id="approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
                            <th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $data)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
                            <td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>		
							<td>
							@canany(['view-passport-request-details','current-user-view-passport-request-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-details','current-user-view-passport-request-details']);
								@endphp
								@if ($hasPermission)   
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
								@endif
								@endcanany
										
							</td>
							<div class="modal fade" id="cancelled-hiring-request-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Cancelled</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger status-cancelled-button"
												data-id="{{ $data->id }}" data-status="cancelled">Submit</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="on-hold-hiring-request-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request On Hold</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary status-onhold-button"
												data-id="{{ $data->id }}" data-status="onhold">Submit</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="closed-hiring-request-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Closed</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															@if(isset($data->shortlistedCandidates))
																@if(count($data->shortlistedCandidates) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Selected Candidates</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<select name="candidate_id[]" id="candidate_id_{{$data->id}}" multiple="true" style="width:100%;"
																		class="candidate_id form-control widthinput" autofocus>
																			@foreach($data->shortlistedCandidates as $shortlistedCandidate)
																				<option value="{{$shortlistedCandidate->id}}" selected>{{$shortlistedCandidate->candidate_name}}</option>
																			@endforeach
																		</select>
																	</div>
																@endif
															@endif
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary status-closed-button"
												data-id="{{ $data->id }}" data-status="closed">Submit</button>
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
	<div class="tab-pane fade show" id="rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
						    <th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $rejectedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
						<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>		
							<td>
							@canany(['view-passport-request-details','current-user-view-passport-request-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-details','current-user-view-passport-request-details']);
								@endphp
								@if ($hasPermission)   
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a>
								@endif
								@endcanany
							
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
@endcanany
@endsection
@push('scripts')
<script type="text/javascript">
	var approved = {!! json_encode($approved) !!};
	$(document).ready(function () {
		var countApproved = 0;
		countApproved = approved.length;
		if(countApproved > 0 ) {
			for(var i=0; i<countApproved; i++) {
				$('#candidate_id_'+approved[i].id).select2({
					allowClear: true,
					placeholder:"Choose Selected Candidates Name",
					dropdownParent: $('#closed-hiring-request-'+approved[i].id)
				});
			}
		}
		$('.status-closed-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        updateFinalStatusHiringrequest(id, status)
	    })
		$('.status-onhold-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        updateFinalStatusHiringrequest(id, status)
	    })
		$('.status-cancelled-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        updateFinalStatusHiringrequest(id, status)
	    })
		function updateFinalStatusHiringrequest(id, status) {
			var comment = $("#comment-"+id).val();
	        let url = '{{ route('employee-hiring-request.final-status') }}';
	        if(status == 'closed') {
	            var message = 'Closed';
				var selectedCandidates = $("#candidate_id_"+id).val();
	        }
			else if(status == 'onhold'){
	            var message = 'On Hold';
				var selectedCandidates = [];
	        }
			else if(status =='cancelled'){
				var message = 'Cancelled';
				var selectedCandidates = [];
			}
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee hiring request ?',function (e) {
	            if (e) {
	                $.ajax({
	                    type: "POST",
	                    url: url,
	                    dataType: "json",
	                    data: {
	                        id: id,
	                        status: status,
	                        comment: comment,
							selectedCandidates: selectedCandidates,
	                        _token: '{{ csrf_token() }}'
	                    },
	                    success: function (data) {
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {

							}
	                    }
	                });
	            }
	
	        }).set({title:"Confirmation"})
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
	$('.hiring-request-delete').on('click',function(){
        let id = $(this).attr('data-id');
        let url =  $(this).attr('data-url');
        var confirm = alertify.confirm('Are you sure you want to Delete this Employee Hiring Request ?',function (e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        _method: 'DELETE',
                        id: 'id',
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        location.reload();
                        alertify.success('Employee Hiring Request Deleted successfully.');
                    }
                });
            }
        }).set({title:"Delete Employee Hiring Request"})
    });
</script>
@endpush