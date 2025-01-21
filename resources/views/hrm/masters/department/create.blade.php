@extends('layouts.main')
@include('layouts.formstyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-department']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Create Department Details</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('department.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="editDivisionForm" name="editDivisionForm" enctype="multipart/form-data" method="POST" action="{{route('department.store')}}">
		@csrf
		<div class="row">
			<div class="col-xxl-12 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>
		</div>
		<br>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Department Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-6 col-lg-6 col-md-6" id="other_leave_type">
						<span class="error">* </span>
						<label for="name" class="col-form-label text-md-end">{{ __('Department Name') }}</label>
						<input type="text" name="name" id="name"
							class="form-control widthinput" placeholder="Enter Department Name"
							aria-label="measurement" aria-describedby="basic-addon2" value="">
					</div>
                    <div class="col-xxl-6 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="division_id" class="col-form-label text-md-end">{{ __('Division Name') }}</label>
							<select name="division_id" id="division_id" multiple="true" class="division_id form-control widthinput" onchange="" autofocus>
							@foreach($divisions as $division)
							<option value="{{$division->id}}">{{$division->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="department_head_id" class="col-form-label text-md-end">{{ __('Department Head Name') }}</label>
							<select name="department_head_id" id="department_head_id" multiple="true" class="department_head_id form-control widthinput" onchange=setHandover() autofocus>
							@foreach($deptHeads as $departmentHead)
							<option value="{{$departmentHead->id}}">{{$departmentHead->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="approval_by_id" class="col-form-label text-md-end">{{ __('Department Head Approval Hand Over To') }}</label>
							<select name="approval_by_id" id="approval_by_id" multiple="true" class="approval_by_id form-control widthinput" onchange="" autofocus>
							@foreach($deptHeads as $approvalBy)
							<option value="{{$approvalBy->id}}">{{$approvalBy->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div mt-1">
							<input type="checkbox" name="is_demand_planning" id="AMS-checkbox" class="dp-checkbox">
                            <label for="dp-checkbox" class="col-form-label text-md-end">{{ __('Is Demand Planning?') }}</label>
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
	var data = {!! json_encode($deptHeads) !!};
	$(document).ready(function () {
        $('#division_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Division Name",
	       });
		$('#department_head_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Department Head Name",
	       });
		$('#approval_by_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Department Head Approval Hand Over To",
	       });
	});	
	jQuery.validator.addMethod("uniqueDepartment", 
	       function(value, element) {
	           var result = false;
	           $.ajax({
	               type:"POST",
	               async: false,
	               url: "{{route('master.uniqueDepartment')}}", // script to validate in server side
	               data: {_token: '{{csrf_token()}}',name: value},
	               success: function(data) {
	                   result = (data == true) ? true : false;
	               }
	           });
	           return result; 
	       }, 
	       "This Department Name is already taken! Try another."
	   );
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
	$('#editDivisionForm').validate({ 
	       rules: {           
	           name: {
	               required: true,
				   uniqueDepartment: true,
	           },
			department_head_id: {
	               required: true,
	           },
               division_id: {
	               required: true,
	           },
			approval_by_id: {
				required: true,
			},
	       },
	   });
       function setHandover() {
        var department_head_id = $("#department_head_id").val();
        if(department_head_id.length == 0) {
			$("#approval_by_id").val('').change();
		}
		else {
			$("#approval_by_id").select2("val", department_head_id);
		}
       }
</script>
@endsection