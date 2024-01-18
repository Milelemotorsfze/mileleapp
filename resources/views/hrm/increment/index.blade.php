@extends('layouts.table')
<style>
	.required-class {
        margin-top: .25rem;
        font-size: 80%;
        color: #fd625e;
    }
	.widthinput {
	height:32px!important;
	}
</style>
@section('content')
@canany(['create-increment','edit-increment','list-all-increment','list-current-user-increment','all-increment-details','current-user-increment-details'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-increment','edit-increment','list-all-increment','list-current-user-increment','all-increment-details','current-user-increment-details']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Employee Salary Increment Info
	</h4>	
	@canany(['create-increment'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-increment']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('increment.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New increment
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
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
                            <th>Request Date</th>
							<th>Employee Name</th>						
                            <th>Employee Code</th>
							<th>Designation</th>
                            <th>Department</th>
							<!-- <th>Location</th> -->
							<th>Basic Salary (AED)</th>
							<th>Other Allowances (AED)</th>
							<th>Total Salary (AED)</th>
							<th>Increment Effective Date</th>
                            <th>Increment Amount (AED)</th>
                            <th>Revised Basic Salary (AED)</th>
                            <th>Revised Other Allowance (AED)</th>
                            <th>Revised Total Salary (AED)</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Updated At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($datas as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<!-- <td>{{ $data->user->empProfile->location->name ?? '' }}</td> -->
							<td>{{ $data->basic_salary ?? ''}}</td>
							<td>{{ $data->other_allowances ?? ''}}</td>
							<td>{{ $data->total_salary ?? ''}}</td>
							<td>{{ $data->increament_effective_date ?? ''}}</td>
                            <td>{{ $data->increment_amount ?? ''}}</td>
                            <td>{{ $data->revised_basic_salary ?? ''}}</td>
							<td>{{ $data->revised_other_allowance ?? ''}}</td>
                            <td>{{ $data->revised_total_salary ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->updatedBy->name ?? ''}}</td>
							<td>
                                @if($data->updated_by != NULL)
                                {{ \Carbon\Carbon::parse($data->updated_at)->format('d M Y, H:i:s') ?? ''}}</td>	
                                @endif							
							<td>							
								@canany(['all-increment-details','current-user-increment-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['all-increment-details','current-user-increment-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('increment.show',$data->id)}}">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
								@endif
								@endcanany
								@canany(['edit-increment'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-increment']);
								@endphp
								@if ($hasPermission) 								
                                <a title="Edit" class="btn btn-sm btn-info" href="{{route('increment.edit',$data->id)}}">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
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
	$(document).ready(function () {
		$('.employee_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Employee Name",
        });

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