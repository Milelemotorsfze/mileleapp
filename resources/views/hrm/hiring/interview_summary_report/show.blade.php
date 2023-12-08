@extends('layouts.table')
<style>
  .texttransform {
    text-transform: capitalize;
  }
  
/* element.style {
} */
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
<div class="card-header">
	<h4 class="card-title"> Candidate Details Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('interview-summary-report.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('interview-summary-report.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
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
		<div class="tab-content">
			<div class="tab-pane fade show active" id="requests">
				<br>
					<div class="card">
					<div class="card-header" style="background-color:#e8f3fd;">
			<div class="row">
				<!-- <div class="col-lg-10 col-md-3 col-sm-6 col-12">
					<h4 class="card-title"><center>Hiring request Info</center></h4>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-6 col-12">
					@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
						@if(isset($data->is_auth_user_can_approve['can_approve']))
							@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button style="float:right;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-hiring-request-{{$data->id}}">
									<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								<button style="float:right; margin-right:5px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-hiring-request-{{$data->id}}">
									<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								@include('hrm.hiring.hiring_request.approve_reject_modal')
							@endif
						@endif
					@endif
					@canany(['edit-employee-hiring-request'])
					@php
					$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-employee-hiring-request']);
					@endphp
					@if ($hasPermission)
						<a style="float:right; margin-right:5px;" title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create-or-edit',$data->id)}}">
							<i class="fa fa-edit" aria-hidden="true"></i> Edit
						</a>
					@endif
					@endcanany
				</div> -->
				<!-- <div class="col-lg-2 col-md-2 col-sm-4 col-12">
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
						<center><label for="choices-single-default" class="form-label"> <strong> Candidate Name</strong></label></center>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
					<center><span>{{ $data->candidate_name ?? '' }}</span></center>
					</div>
				</div> -->
				<div class="col-lg-4 col-md-4 col-sm-4 col-12">
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
						<center><label for="choices-single-default" class="form-label"> <strong> Hiring Request UUID</strong></label></center>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
					<center><span>{{ $data->employeeHiringRequest->uuid ?? '' }}</span></center>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-12">
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
						<center><label for="choices-single-default" class="form-label"> <strong> Job Position</strong></label></center>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
					<center><span>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</span></center>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-12">
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
				<center><label for="choices-single-default" class="form-label"> <strong> Job Location</strong></label></center>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
					<center><span>{{ $data->employeeHiringRequest->questionnaire->workLocation->name ?? '' }}</span></center>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-12">
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-12">
				</div>
			</div>
		</div>
			@include('hrm.hiring.interview_summary_report.details')
		</div>
	</div>
</div>
</div>
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