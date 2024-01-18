@extends('layouts.main')
@include('layouts.formstyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@canany(['edit-insurance'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-insurance']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Edit Employee Insurance</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('insurance.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
		<form id="insuranceForm" name="insuranceForm" enctype="multipart/form-data" method="POST" action="{{route('insurance.update',$data->id)}}">
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
				<h4 class="card-title">Employee Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
                        <select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" onchange="" autofocus>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}" @if($data->employee_id == $employee->id) selected @endif>{{$employee->name}}</option>
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
				<h4 class="card-title">Insurance Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-3 col-md-3">
                    	<span class="error">* </span>
						<label for="insurance_policy_number" class="col-form-label text-md-end">{{ __('Insurance Policy Number') }}</label>						
						<input type="text" class="form-control widthinput" name="insurance_policy_number" id="insurance_policy_number" 
						placeholder="Insurance Policy Number" value="{{$data->insurance_policy_number ?? ''}}"/>                               
                    </div>
					<div class="col-xxl-3 col-lg-3 col-md-3">
                    	<span class="error">* </span>
						<label for="insurance_card_number" class="col-form-label text-md-end">{{ __('Insurance Card Number') }}</label>						
						<input type="text" class="form-control widthinput" name="insurance_card_number" id="insurance_card_number" 
						placeholder="Insurance Card Number" value="{{$data->insurance_card_number ?? ''}}"/>                               
                    </div>                
                    <div class="col-xxl-2 col-lg-2 col-md-2">
                    	<span class="error">* </span>
						<label for="insurance_policy_start_date" class="col-form-label text-md-end">{{ __('Insurance Policy Start Date') }}</label>						
						<input type="date" class="form-control widthinput" name="insurance_policy_start_date" id="insurance_policy_start_date" 
						onkeydown="return false;" value="{{$data->insurance_policy_start_date ?? ''}}"/>                               
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2">
                    	<span class="error">* </span>
						<label for="insurance_policy_end_date" class="col-form-label text-md-end">{{ __('Insurance Policy End Date') }}</label>
						<input type="date" name="insurance_policy_end_date" id="insurance_policy_end_date"
								class="form-control widthinput" placeholder="Enter Ticket Allowance PO Number"
								 aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->insurance_policy_end_date ?? ''}}">
                    </div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<span class="error">* </span>
						<label for="insurance_image" class="col-form-label text-md-end">{{ __('Insurance Copy Upload') }}</label>
						<input type="file" class="form-control widthinput" id="insurance" name="insurance_image" accept="application/pdf, image/*">
					</div>
				</div>
			</div>
		</div>		
		</br>
		<div class="card preview-div">
			<div class="card-body">
				<div class="row">			
					<div class="col-lg-12 col-md-12 col-sm-12 mt-12">
						<span class="fw-bold col-form-label text-md-end" id="insurance-label"></span>
						<div id="insurance-preview">
						@if(isset($data->insurance_image))
						<div id="insurance-preview1">
							<div class="row">
								<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
									<h6 class="fw-bold text-center mb-1" style="float:left;">insurance</h6>
								</div>
								<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
									<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
									<a href="{{ url('hrm/employee/insurance/' . $data->insurance_image) }}" download class="text-white">
									Download
									</a>
									</button>
									<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
										data-file-type="insurance"> Delete</button>
								</div>
							</div>
							<iframe src="{{ url('hrm/employee/insurance/' . $data->insurance_image) }}" height="800" alt="insurance"></iframe>                                                                           
						</div>
						@endif										
						</div>
					</div>
					<input type="hidden" id="insurance-file-delete" name="is_insurance_delete" value="">   									
				</div>
			</div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="edit" id="submit">Submit</button>
		</div></br></br></br></br></br>
	</form>
</div>
<div class="overlay"></div>
@endif
@endcanany
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	
	var data = {!! json_encode($employees) !!};
    var oldData = {!! json_encode($data) !!};
	const fileInputinsurance = document.querySelector("#insurance");
	const previewFileinsurance = document.querySelector("#insurance-preview");
	fileInputinsurance.addEventListener("change", function(event) {
		$('.preview-div').attr('hidden', false);
		const files = event.target.files;
		while (previewFileinsurance.firstChild) {
			previewFileinsurance.removeChild(previewFileinsurance.firstChild);
		}
		const file = files[0];
		if (file.type.match("application/pdf"))
		{
			document.getElementById('insurance-label').textContent="insurance";
			const objectUrl = URL.createObjectURL(file);
			const iframe = document.createElement("iframe");
			iframe.src = objectUrl;
			iframe.height = "800";
			previewFileinsurance.appendChild(iframe);
		}
		else if (file.type.match("image/*"))
		{
			document.getElementById('insurance-label').textContent="insurance";
			const objectUrl = URL.createObjectURL(file);
			const image = new Image();
			image.src = objectUrl;
			iframe.height = "800";
			previewFileinsurance.appendChild(image);
		}
    });
	$(document).ready(function () {
		if(oldData.user.emp_profile.employee_code != null) {
			document.getElementById('employee_code').textContent=oldData.user.emp_profile.employee_code;
		}
		if(oldData.user.emp_profile.company_joining_date != null) {
			document.getElementById('joining_date').textContent=oldData.user.emp_profile.company_joining_date;
		}
		if(oldData.user.emp_profile.designation != null) {
			document.getElementById('designation').textContent=oldData.user.emp_profile.designation.name;
		}
		if(oldData.user.emp_profile.department != null) {
			document.getElementById('department').textContent=oldData.user.emp_profile.department.name;
		}
		if(oldData.user.emp_profile.location != null) {
			document.getElementById('location').textContent=oldData.user.emp_profile.location.name;
		}		
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
	$('#insuranceForm').validate({ // initialize the plugin
        rules: {
			employee_id: {
                required: true,
            },
			insurance_policy_number: {
				required: true,
				alphaNumeric:true,
			}, 
			insurance_card_number: {
				required: true,
				alphaNumeric:true,
			}, 
			insurance_policy_start_date: {
				required: true,
			},           
            insurance_policy_end_date: {
                required: true,
            },
        },
    });
	$('.delete-button').on('click',function() {
		var fileType = $(this).attr('data-file-type');
		if (confirm('Are you sure you want to Delete this item ?')) {
				$('#insurance-preview1').remove();
				$('#insurance-file-delete').val(1);
		}
    });
</script>
@endsection