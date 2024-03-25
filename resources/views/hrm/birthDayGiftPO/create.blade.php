@extends('layouts.main')
@include('layouts.formstyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-birthday-po']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Create Employee Birthday Gift PO</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('birthday_gift.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="employeeBirthdayGiftPOForm" name="employeeBirthdayGiftPOForm" enctype="multipart/form-data" method="POST" action="{{route('birthday_gift.store')}}">
		@csrf
		<div class="row">
			<div class="col-xxl-12 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>
		</div>
		<br>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Employee Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
						<select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" onchange="" autofocus>
							@foreach($employees as $employee)
							<option value="{{$employee->id}}">{{$employee->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="employee_code_div">
						<center><label for="employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
						<center><span id="employee_code"></span></center>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="designation_div">
						<center><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
						<center><span id="designation"></span></center>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="department_div">
						<center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
						<center><span id="department"></span></center>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="joining_date_div">
						<center><label for="joining_date" class="col-form-label text-md-end"><strong>{{ __('Joining Date') }}</strong></label></center>
						<center><span id="joining_date"></span></center>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="location_div">
						<center><label for="location" class="col-form-label text-md-end"><strong>{{ __('Location') }}</strong></label></center>
						<center><span id="location"></span></center>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Birthday Gift PO Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="po_year" class="col-form-label text-md-end">{{ __('Birthday Gift PO For Year') }}</label>						
						<input type="text" class="form-control widthinput" name="po_year" id="po_year" onkeydown="return false;" value=""/>                               
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="po_number" class="col-form-label text-md-end">{{ __('Birthday Gift PO Number') }}</label>
						<input type="text" name="po_number" id="po_number"
							class="form-control widthinput" placeholder="Enter Birthday Gift PO Number"
							aria-label="measurement" aria-describedby="basic-addon2" value="">
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
		</br></br></br></br></br>
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
	var data = {!! json_encode($employees) !!};
	$(document).ready(function () {
		$("#po_year").yearpicker({
	           startYear: 2019,
	           endYear: 2050,
	       });
		$("#employee_code_div").hide();
		$("#joining_date_div").hide();
		$("#designation_div").hide();
		$("#department_div").hide();
		$("#location_div").hide();
		$('#employee_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Employee Name",
	       });
	       $('#employee_id').on('change', function() {
	           var selectedEmpId = $(this).val();
	           if(selectedEmpId == '') {
	               $("#employee_code_div").hide();
	               $("#joining_date_div").hide();
	               $("#designation_div").hide();
	               $("#department_div").hide();
	               $("#location_div").hide();
	           }
	           else {
				document.getElementById('employee_code').textContent = '';
				document.getElementById('joining_date').textContent = '';
				document.getElementById('designation').textContent = '';
				document.getElementById('department').textContent = '';
				document.getElementById('location').textContent = '';
	               for (var i = 0; i < data.length; i++) {
	                   if (data[i].id == Number(selectedEmpId)) {
						if(data[i].emp_profile.employee_code != null) {
							document.getElementById('employee_code').textContent=data[i].emp_profile.employee_code;
						}
						if(data[i].emp_profile.company_joining_date != null) {
							document.getElementById('joining_date').textContent=data[i].emp_profile.company_joining_date;
						}
						if(data[i].emp_profile.designation != null) {
							document.getElementById('designation').textContent=data[i].emp_profile.designation.name;
						}
						if(data[i].emp_profile.department != null) {
							document.getElementById('department').textContent=data[i].emp_profile.department.name;
						}
						if(data[i].emp_profile.location != null) {
							document.getElementById('location').textContent=data[i].emp_profile.location.name;
						}
	                   }
	               }
	               $("#employee_code_div").show();
	               $("#joining_date_div").show();
	               $("#designation_div").show();
	               $("#department_div").show();
	               $("#location_div").show();
	           }     
	       });
	});	
	jQuery.validator.setDefaults({
	       errorClass: "is-invalid",
	       errorElement: "p",
	       errorPlacement: function ( error, element ) {
	           error.addClass( "invalid-feedback font-size-13" );
	           if ( element.prop( "type" ) === "checkbox" ) {
	               error.insertAfter( element.parent( "label" ) );
	           }
	           else if (element.hasClass("select2-hidden-accessible")) {
	               element = $("#select2-" + element.attr("id") + "-container").parent();
	               error.insertAfter(element);
	           }
			else if (element.parent().hasClass('input-group')) {
	               error.insertAfter(element.parent());
	           }
	           else {
	               error.insertAfter( element );
	           }
	       }
	   });
	jQuery.validator.addMethod("alphaNumeric", function(value, element) {
		return this.optional(element) || /^[a-zA-Z0-9 ]*$/.test(value);
	}, "Letters and Numbers only Allowed");
	$('#employeeBirthdayGiftPOForm').validate({ 
	       rules: {
			employee_id: {
	               required: true,
	           },
			po_year: {
				required: true,
			},           
	           po_number: {
	               required: true,
				alphaNumeric:true,
	           },
	       },
	   });
</script>
@endsection