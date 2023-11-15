@extends('layouts.table')
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
<div class="row">
  <div class="col-xxl-6 col-lg-6 col-md-6">
  <div class="col-xxl-12 col-lg-12 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Request Date :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->request_date ?? '' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xxl-12 col-lg-12 col-md-12">
    <div class="card">
			<div class="card-header">
				<h4 class="card-title">Department Information</h4>
			</div>
			<div class="card-body">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Department Name :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->department_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Department Location :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->department_location ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Requested By :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->requested_by_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Requested Job Title :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->requested_job_name ?? '' }}</span>
          </div>
        </div>
			</div>
		</div>
  </div>
  <div class="col-xxl-12 col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Position Information</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Reporting To With Position :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->reporting_to_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Experience Level :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->experience_level_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Salary Range(AED) :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Work Time :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Number Of Openings :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->number_of_openings ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Type Of Role :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->type_of_role_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Replacement For Employee :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->replacement_for_employee_name ?? '' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xxl-12 col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Detailed Explanation Of New Hiring</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
              <span>{{$data->explanation_of_new_hiring ?? ''}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <div class="col-xxl-6 col-lg-6 col-md-6">
    <div class="card">
			<div class="card-header">
				<h4 class="card-title">History</h4>
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