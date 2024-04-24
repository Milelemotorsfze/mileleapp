@extends('layouts.main')
@include('layouts.formstyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-designation-approvals']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Edit Designation Approvals Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('designation-approvals.edit',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('designation-approvals.edit',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif 
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('designation-approvals.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<form id="editDesigApprForm" name="editDesigApprForm" enctype="multipart/form-data" method="POST" action="{{route('designation-approvals.update',$data->id)}}">
		@csrf
		@method("PUT")
		<div class="row">
			<div class="col-xxl-12 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>
		</div>
		<br>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Designation Approval Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<input type="hidden" value="{{$data->id ?? ''}}" name="id">
					<div class="col-xxl-4 col-lg-4 col-md-4" id="other_leave_type">
						<span class="error">* </span>
						<label for="name" class="col-form-label text-md-end">{{ __('Designation Name') }}</label>
						<input type="text" name="approved_by_position" id="approved_by_position"
							class="form-control widthinput" placeholder="Enter Designation Name"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->approved_by_position ?? ''}}" readonly>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="approved_by_id" class="col-form-label text-md-end">{{ __('Approved By') }}</label>
							<select name="approved_by_id" id="approved_by_id" multiple="true" class="approved_by_id form-control widthinput" autofocus>
							@foreach($users as $departmentHead)
							<option value="{{$departmentHead->id}}" @if($data->approved_by_id == $departmentHead->id) selected @endif>{{$departmentHead->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="handover_to_id" class="col-form-label text-md-end">{{ __('Approval Hand Over To') }}</label>
							<select name="handover_to_id" id="handover_to_id" multiple="true" class="handover_to_id form-control widthinput" onchange="" autofocus>
							@foreach($users as $approvalBy)
							<option value="{{$approvalBy->id}}" @if($data->handover_to_id == $approvalBy->id) selected @endif>{{$approvalBy->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
<div class="overlay"></div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	var data = {!! json_encode($users) !!};
	$(document).ready(function () {
        $('#division_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Division Name",
	       });
		$('#approved_by_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Designation Approval By Name",
	       });
		$('#handover_to_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Designation Approval Hand Over To",
	       });
	});	
	jQuery.validator.setDefaults({
	    errorClass: "is-invalid",
	    errorElement: "p",     
	});
	jQuery.validator.setDefaults({
	       errorClass: "is-invalid",
	       errorElement: "p",
	       errorPlacement: function ( error, element ) {
	           error.addClass( "invalid-feedback font-size-13" );
			if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
	               if (!element.val() || element.val().length === 0) {
	                   console.log("Error is here with length", element.val().length);
	                   error.addClass('select-error');
	                   error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
	               } else {
	                   console.log("No error");
	               }
	           }
	           else {
	               error.insertAfter( element );
	           }
	       }
	   });
	$('#editDesigApprForm').validate({ 
	       rules: {           
			approved_by_position: {
	               required: true,
	           },
			approved_by_id: {
	               required: true,
	           },
			   handover_to_id: {
				required: true,
			},
			id: {
				required: true,
			},
	       },
	   });
</script>
@endsection