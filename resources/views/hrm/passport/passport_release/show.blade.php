@extends('layouts.table')
<style>
	.texttransform {
	text-transform: capitalize;
	}
	.nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link {
	width: 99%;
	border: 1px solid #4ba6ef !important;
	background-color: #c1e1fb !important;
	}
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	color: black!important;
	background-image: linear-gradient(to right,#4ba6ef,#4ba6ef,#0065ac)!important;
	}
	.nav-link:focus{
	color: black!important;
	}
	.nav-link:hover {
	color: black!important;
	}
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-details','current-user-view-passport-request-details']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Employee Passport Release Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('passport_release.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('passport_release.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
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
<div class="card-body">
	<div class="row">
		<div class="col-xxl-6 col-lg-6 col-md-12">
			<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Employee Passport Release Details</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Employee Name :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->user->name ?? '' }} </span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Employee Code :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->user->empProfile->employee_code ?? '' }}</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Designation :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->user->empProfile->designation->name ?? '' }}</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Department :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->user->empProfile->department->name ?? '' }}</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Reporting Manager :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->reportingManager->name ?? '' }}</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Division Head :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->divisionHead->name ?? '' }}</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> HR Manager :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->hrManager->name ?? '' }}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">
							<center>History</center>
						</h4>
					</div>
					<div class="card-body">
						<div class="col-xxl-12 col-lg-12 col-md-12">
							@if(isset($data->history))
							@foreach($data->history as $history)
							<div class="row">
								<div class="col-xxl-1 col-lg-1 col-md-1">
									<img src="{{ asset('icons/' . $history->icon) }}" style="width:30px;height:30px;">
								</div>
								<div class="col-xxl-11 col-lg-11 col-md-11">
									{{$history->message ?? ''}} </br> <span style="color:gray">
									@if($history->created_at != '')
									{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y, H:i:s') }}
									@endif
									</span>
								</div>
							</div>
							</br>
							@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">
						<center>Approvals By</center>
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">Employee</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{ $data->user->name ?? '' }}
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->release_action_by_employee =='pending') badge-soft-info 
												@elseif($data->release_action_by_employee =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->release_action_by_employee ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->release_employee_action_at != '')
											{{ \Carbon\Carbon::parse($data->release_employee_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->release_comments_by_employee ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">Reporting Manager</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{ $data->reportingManager->name ?? ''}}
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->release_action_by_department_head =='pending') badge-soft-info 
												@elseif($data->release_action_by_department_head =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->release_action_by_department_head ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->release_department_head_action_at != '')
											{{ \Carbon\Carbon::parse($data->release_department_head_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->release_comments_by_department_head ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">Divison Head</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->divisionHead->name ?? ''}}
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->release_action_by_department_head =='pending') badge-soft-info 
												@elseif($data->release_action_by_department_head =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->release_action_by_department_head ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->release_division_head_action_at != '')
											{{ \Carbon\Carbon::parse($data->release_division_head_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->release_comments_by_division_head ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">HR Manager</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{ $data->hrManager->name ?? '' }}
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->release_action_by_hr_manager =='pending') badge-soft-info 
												@elseif($data->release_action_by_hr_manager =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->release_action_by_hr_manager ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->release_hr_manager_action_at != '')
											{{ \Carbon\Carbon::parse($data->release_hr_manager_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->release_comments_by_hr_manager ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
@push('scripts')
<script>
	$(document).ready(function () {
	
	    $('.delete-button').on('click',function(){
	        let id = $(this).attr('data-id');
	        let url =  $(this).attr('data-url');
	        var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
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
	                        alertify.success('Item Deleted successfully.');
	                    }
	                });
	            }
	        }).set({title:"Delete Item"})
	    });
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