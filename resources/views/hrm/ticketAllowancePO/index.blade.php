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
@canany(['create-ticket-po','edit-ticket-po','view-ticket-listing','view-ticket-listing-of-current-user','view-ticket-details','view-ticket-details-of-current-user'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-ticket-po','edit-ticket-po','view-ticket-listing','view-ticket-listing-of-current-user','view-ticket-details','view-ticket-details-of-current-user']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Employee Ticket Allowance PO Info
	</h4>	
	@canany(['create-ticket-po'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-ticket-po']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('ticket_allowance.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Ticket Allowance PO
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
							<th>Location</th>
							<th>Ticket Allowance Eligibility Year</th>
							<th>Ticket Allowance Eligibility Date</th>
							<th>Ticket Allowance PO for Year</th>
							<th>Ticket Allowance PO Number</th>
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
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{ $data->eligibility_year ?? ''}}</td>
							<td>{{ $data->eligibility_date ?? ''}}</td>
							<td>{{ $data->po_year ?? ''}}</td>
							<td>{{ $data->po_number ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->updatedBy->name ?? ''}}</td>
							<td>
                                @if($data->updated_by != NULL)
                                {{ \Carbon\Carbon::parse($data->updated_at)->format('d M Y, H:i:s') ?? ''}}</td>	
                                @endif							
							<td>							
								@canany(['view-ticket-details','view-ticket-details-of-current-user'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-ticket-details','view-ticket-details-of-current-user']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('ticket_allowance.show',$data->id)}}">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
								@endif
								@endcanany
								@canany(['edit-ticket-po'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-ticket-po']);
								@endphp
								@if ($hasPermission) 								
                                <a title="Edit" class="btn btn-sm btn-info" href="{{route('ticket_allowance.edit',$data->id)}}">
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
	function inputNumberAbs(currentPriceInput) {
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