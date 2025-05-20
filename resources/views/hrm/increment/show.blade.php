@extends('layouts.table')
<style>
	tr {
	border:1px solid #e9e9ef !important;
	}
	td {
	padding-top:10px!important;
	padding-bottom:10px!important;
	padding-right:10px!important;
	padding-left:10px!important;
	}
	th {
	padding-top:10px!important;
	padding-bottom:10px!important;
	padding-right:10px!important;
	padding-left:10px!important;
	}
	.texttransform {
	text-transform: capitalize;
	}
	.nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link {
	width: 99%;
	border: 1px solid #4ba6ef !important;
	background-color: #c1e1fb !important;
	}
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	/* color: black!iortant; */
	/* background-image: linear-gradient(to right,#4ba6ef,#4ba6ef,#0065ac)!important; */
	background: #072c47 !important;
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
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['all-increment-details','current-user-increment-details']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Employee Increment Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('increment.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('increment.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
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
		<div class="col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Employee Details</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Employee Name :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->user->name ?? '' }}</span>
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
							<label for="choices-single-default" class="form-label"> Location :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->user->empProfile->location->name ?? '' }}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Passport Number :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->user->empProfile->passport_number ?? '' }}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Joining Date :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>
							@if($data->user->empProfile->company_joining_date != NULL)
							{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
							@endif
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Increment Details</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Basic Salary (AED) :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->basic_salary ?? ''}}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Other Allowances (AED) :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->other_allowances ?? ''}}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Total Salary (AED) :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>
							{{ $data->total_salary ?? ''}}
							</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Increment Effective Date :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>
							@if($data->increament_effective_date != NULL)
							{{\Carbon\Carbon::parse($data->increament_effective_date)->format('d M Y') ?? ''}}
							@endif
							</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Increment Amount (AED) :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->increment_amount ?? ''}}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Revised Basic Salary (AED) :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->revised_basic_salary ?? ''}}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Revised Other Allowance (AED) :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->revised_other_allowance ?? ''}}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Revised Total Salary (AED) :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->revised_total_salary ?? ''}}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Date :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>
							@if($data->created_at != NULL)
							{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
							@endif
							</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Created By :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->createdBy->name ?? '' }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			@if(isset($data->increment_image))
			<div class="card preview-div">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 mt-12">
							<span class="fw-bold col-form-label text-md-end" id="increment-label"></span>
							<div id="increment-preview">
								<div id="increment-preview1">
									<div class="row">
										<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
											<h6 class="fw-bold text-center mb-1" style="float:left;">increment</h6>
										</div>
										<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
											<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('hrm/employee/increment/' . $data->increment_image) }}" download class="text-white">
											Download
											</a>
											</button>                                           
										</div>
									</div>
									<iframe src="{{ url('hrm/employee/increment/' . $data->increment_image) }}" height="500" alt="increment"></iframe>                                                                           
								</div>
							</div>
						</div>
						<input type="hidden" id="increment-file-delete" name="is_increment_delete" value="">   									
					</div>
				</div>
			</div>
			@endif	
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">All Increment Details</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<table>
							<thead>
								<tr>
									<th>Sl No</th>
									<th>Basic Salary (AED)</th>
									<th>Other Allowances (AED)</th>
									<th>Total Salary (AED)</th>
									<th>Increment Effective Date</th>
									<th>Increment Amount (AED)</th>
									<th>Revised Basic Salary (AED)</th>
									<th>Revised Other Allowance (AED)</th>
									<th>Revised Total Salary (AED)</th>
									<th>Created At</th>
									<th>Created By</th>
									<th>Updated At</th>
									<th>Updated By</th>
								</tr>
							</thead>
							<tbody>
								<div hidden>{{$i=0;}}</div>
								@foreach($all as $one)
								<tr>
									<td>{{ ++$i }}</td>
									<td>{{ $one->basic_salary ?? ''}}</td>
									<td>{{ $one->other_allowances ?? ''}}</td>
									<td>{{ $one->total_salary ?? ''}}</td>
									<td>{{ $one->increament_effective_date ?? ''}}</td>
									<td>{{ $one->increment_amount ?? ''}}</td>
									<td>{{ $one->revised_basic_salary ?? ''}}</td>
									<td>{{ $one->revised_other_allowance ?? ''}}</td>
									<td>{{ $one->revised_total_salary ?? ''}}</td>
									<td>
										@if($one->created_at != NULL)
										{{\Carbon\Carbon::parse($one->created_at)->format('d M Y, H:i:s') ?? ''}}
										@endif
									</td>
									<td>{{ $one->createdBy->name ?? ''}}</td>
									<td>
										@if($one->updated_by != NULL)
										{{\Carbon\Carbon::parse($one->updated_at)->format('d M Y, H:i:s') ?? ''}}
										@endif
									</td>
									<td> @if($one->updated_by != NULL)
										{{$one->updatedBy->name ?? ''}}
										@endif
									</td>
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
	function inputNumberAbs(currentPriceInput) {
	    var id = currentPriceInput.id;
	    var input = document.getElementById(id);
	    var val = input.value;
	    val = val.replace(/^0+|[^\d.]/g, '');
	    if(val.split('.').lengtd>2)
	    {
	        val =val.replace(/\.+$/,"");
	    }
	    input.value = val;
	}
</script>
@endpush