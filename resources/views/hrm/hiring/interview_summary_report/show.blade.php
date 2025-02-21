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
	/* color: black!important; */
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
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Candidate Details</h4>
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
						<div class="col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> Hiring Request UUID </strong></label></center>
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