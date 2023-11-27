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
	<h4 class="card-title"> Employee Hiring Request Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
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
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
      
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#requests"> Hiring Request</a>
		</li>
        <li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approvals-and-history"> Approvals and History</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#questionnaire-and-job-descriptions">Questionnaire and Job Description</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#cancelled-hiring-requests">Interview Summary Report</a>
		</li>
	</ul>
</div>
<div class="tab-content">
	<div class="tab-pane fade show active" id="requests">
        <br>
            <div class="card">
                <div class="card-header" style="background-color:#e8f3fd;">
                    <h4 class="card-title"><center>Hiring request Info</center></h4>
                </div>
                <div class="card-body">
                    @include('hrm.hiring.hiring_request.details')
                </div>
            </div>
	</div>
</div>
<div class="tab-content">
	<div class="tab-pane fade show" id="approvals-and-history">
        <br>
        <div class="row">
        <div class="col-xxl-6 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-header" style="background-color:#e8f3fd;">
                <h4 class="card-title"><center>Approvals By</center></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <center><h4 class="card-title">Team Lead / Reporting Manager</h4></center>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Name :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->department_head_name ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Status :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                    <label class="badge texttransform @if($data->action_by_department_head =='pending') badge-soft-info 
                                    @elseif($data->action_by_department_head =='approved') badge-soft-success 
                                    @else badge-soft-danger @endif">{{$data->action_by_department_head ?? ''}}</label>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Date & Time :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->department_head_action_at ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Comments :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->comments_by_department_head ?? ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <center><h4 class="card-title">Recruiting Manager</h4></center>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Name :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->hiring_manager_name ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Status :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                    <label class="badge texttransform @if($data->action_by_hiring_manager =='pending') badge-soft-info 
                                    @elseif($data->action_by_hiring_manager =='approved') badge-soft-success 
                                    @else badge-soft-danger @endif">{{$data->action_by_hiring_manager ?? ''}}</label>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Date & Time :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->hiring_manager_action_at ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Comments :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->comments_by_hiring_manager ?? ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <center><h4 class="card-title">Division Head</h4></center>
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
                                          <label class="badge texttransform @if($data->action_by_division_head =='pending') badge-soft-info 
                                    @elseif($data->action_by_division_head =='approved') badge-soft-success 
                                    @else badge-soft-danger @endif">{{$data->action_by_division_head ?? ''}}</label>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Date & Time :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->division_head_action_at ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Comments :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->comments_by_division_head ?? ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <center><h4 class="card-title">HR Manager</h4></center>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Name :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->hr_manager_name ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Status :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                    <label class="badge texttransform @if($data->action_by_hr_manager =='pending') badge-soft-info 
                                    @elseif($data->action_by_hr_manager =='approved') badge-soft-success 
                                    @else badge-soft-danger @endif">{{$data->action_by_hr_manager ?? ''}}</label>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Date & Time :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->hr_manager_action_at ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Comments :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->comments_by_hr_manager ?? ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		</div>
        <div class="col-xxl-6 col-lg-6 col-md-6">
        <div class="card">
        <div class="card-header" style="background-color:#e8f3fd;">
            <h4 class="card-title"><center>History</center></h4>
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
                {{$history->message ?? ''}} </br> <span style="color:gray">{{$history->created_at ?? ''}}</span>
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
    </div>
</div>
<div class="tab-content">
	<div class="tab-pane fade show" id="questionnaire-and-job-descriptions">
        <br>
        <div class="row">
            @include('hrm.hiring.hiring_request.questionnaire_details')
            @include('hrm.hiring.hiring_request.job_description_details')
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