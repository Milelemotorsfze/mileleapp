@extends('layouts.main')
@include('layouts.formstyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@canany(['create-insurance'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-insurance']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Create Employee Salary Increment</h4>
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
		<form id="incrementForm" name="incrementForm" method="POST" action="{{route('increment.store')}}" enctype="multipart/form-data" target="_self">
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
				<h4 class="card-title">Salary Increment Information</h4>
			</div>
			<div class="card-body">
				<div class="row"> 
					<div class="col-xxl-4 col-lg-4 col-md-4" id="basic_salary_div">
                        <center><label for="basic_salary" class="col-form-label text-md-end"><strong>{{ __('Basic Salary') }}</strong></label></center>
                        <center><span id="basic_salary"></span></center>
                    </div>  
					<div class="col-xxl-4 col-lg-4 col-md-4" id="other_allowances_div">
                        <center><label for="other_allowances" class="col-form-label text-md-end"><strong>{{ __('Other Allowances') }}</strong></label></center>
                        <center><span id="other_allowances"></span></center>
                    </div>  
					<div class="col-xxl-4 col-lg-4 col-md-4" id="total_salary_div">
                        <center><label for="total_salary" class="col-form-label text-md-end"><strong>{{ __('Total Salary') }}</strong></label></center>
                        <center><span id="total_salary"></span></center>
                    </div> 
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="increament_effective_date" class="col-form-label text-md-end">{{ __('Increment Effective Date') }}</label>
						<input type="date" name="increament_effective_date" id="increament_effective_date" class="form-control widthinput" value="">
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="increment_amount" class="col-form-label text-md-end">{{ __('Increment Amount (AED)') }}</label>
						<div class="input-group">
							<input id="increment_amount" type="number" min="0" step="any" class="form-control widthinput @error('increment_amount') is-invalid @enderror"
								name="increment_amount" placeholder="Increment Amount (AED)" value="{{ old('increment_amount') }}"  autocomplete="increment_amount" autofocus>
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>	
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="revised_basic_salary" class="col-form-label text-md-end">{{ __('Revised Basic Salary (AED)') }}</label>
						<div class="input-group">
							<input id="revised_basic_salary" type="number" min="0" step="any" class="form-control widthinput @error('revised_basic_salary') is-invalid @enderror"
								name="revised_basic_salary" placeholder="Revised Basic Salary (AED)" value="{{ old('revised_basic_salary') }}"  autocomplete="revised_basic_salary" autofocus>
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="revised_other_allowance" class="col-form-label text-md-end">{{ __('Revised Other Allowance (AED)') }}</label>
						<div class="input-group">
							<input id="revised_other_allowance" type="number" min="0" step="any" class="form-control widthinput @error('revised_other_allowance') is-invalid @enderror"
								name="revised_other_allowance" placeholder="Revised Other Allowance (AED)" value="{{ old('revised_other_allowance') }}"  autocomplete="revised_other_allowance" autofocus>
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>	
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="revised_total_salary" class="col-form-label text-md-end">{{ __('Revised Total Salary (AED)') }}</label>
						<div class="input-group">
							<input id="revised_total_salary" type="number" min="0" step="any" class="form-control widthinput @error('revised_total_salary') is-invalid @enderror"
								name="revised_total_salary" placeholder="Revised Total Salary (AED)" value="{{ old('revised_total_salary') }}"  autocomplete="revised_total_salary" autofocus>
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="salaryIncrement_image" class="col-form-label text-md-end">{{ __('Related Documents Upload') }}</label>
						<div class="input-group">
						<input type="file" class="form-control widthinput" multiple id="salaryIncrement-file" name="salaryIncrement[]"
                                                            placeholder="Upload salaryIncrement (First & Second page)" accept="application/pdf, image/*">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
		<div class="card preview-div" hidden>
			<div class="card-body">
				<div class="row">			
					<div class="col-lg-12 col-md-12 col-sm-12 mt-12">
						<span class="fw-bold col-form-label text-md-end" id="salaryIncrement-label"></span>
						<div id="salaryIncrement-file-preview">
                        </div>
					</div>
					<input type="hidden" id="salaryIncrement-file-delete" name="is_salaryIncrement_delete" value="">   									
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
@endif
@endcanany
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	const fileInputsalaryIncrement = document.querySelector("#salaryIncrement-file");
	const previewFilesalaryIncrement = document.querySelector("#salaryIncrement-file-preview");
	fileInputsalaryIncrement.addEventListener("change", function(event) {
		$('.preview-div').attr('hidden', false);
		const files = event.target.files;
		document.getElementById('salaryIncrement-label').textContent="salaryIncrement";
		for (let i = 0; i < files.length; i++) {
			const file = files[i];
			if (file.type.match("application/pdf")) {
				const objectUrl = URL.createObjectURL(file);
				const iframe = document.createElement("iframe");
				iframe.src = objectUrl;
				previewFilesalaryIncrement.appendChild(iframe);
			} else if (file.type.match("image/*")) {
				const objectUrl = URL.createObjectURL(file);
				const image = new Image();
				image.src = objectUrl;
				previewFilesalaryIncrement.appendChild(image);
			}
		}
	});
	var data = {!! json_encode($employees) !!};
	$(document).ready(function () {
		$("#employee_code_div").hide();
		$("#joining_date_div").hide();
		$("#designation_div").hide();
		$("#department_div").hide();
		$("#location_div").hide();
		$("#basic_salary_div").hide();
		$("#other_allowances_div").hide();		
		$("#total_salary_div").hide();
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
				$("#basic_salary_div").hide();
                $("#other_allowances_div").hide();
                $("#total_salary_div").hide();
            }
            else {
				document.getElementById('employee_code').textContent = '';
				document.getElementById('joining_date').textContent = '';
				document.getElementById('designation').textContent = '';
				document.getElementById('department').textContent = '';
				document.getElementById('location').textContent = '';
				document.getElementById('basic_salary').textContent = '';
				document.getElementById('other_allowances').textContent = '';
				document.getElementById('total_salary').textContent = '';
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
						if(data[i].emp_profile.basic_salary != null) {
							document.getElementById('basic_salary').textContent=data[i].emp_profile.basic_salary + ' AED';
						}
						if(data[i].emp_profile.other_allowances != null) {
							document.getElementById('other_allowances').textContent=data[i].emp_profile.other_allowances + ' AED';
						}
						if(data[i].emp_profile.total_salary != null) {
							document.getElementById('total_salary').textContent=data[i].emp_profile.total_salary + ' AED';
						}
                    }
                }
                $("#employee_code_div").show();
                $("#joining_date_div").show();
                $("#designation_div").show();
                $("#department_div").show();
                $("#location_div").show();
				$("#basic_salary_div").show();
                $("#other_allowances_div").show();
                $("#total_salary_div").show();
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
	jQuery.validator.addMethod(
		"money",
		function(value, element) {
			var isValidMoney = /^\d{0,5}(\.\d{0,2})?$/.test(value);
			return this.optional(element) || isValidMoney;
		},
		"Please enter a valid amount "
	);
	$('#incrementForm').validate({ // initialize the plugin
        rules: {
			employee_id: {
                required: true,
            },
			increament_effective_date: {
				required: true,
			}, 
			increment_amount: {
				required: true,
				money: true,
			}, 
			revised_basic_salary: {
				required: true,
				money: true,
			},           
            revised_other_allowance: {
                required: true,
				money: true,
            },
			revised_total_salary: {
				required: true,
				money: true,
			}
			// increment_docs: { 
			// 	required: true,
			// 	extension: "docx|rtf|doc|pdf|jpg|jpeg",
			// },
        },
    });
	// $('.delete-button').on('click',function() {
	// 	var fileType = $(this).attr('data-file-type');
	// 	if (confirm('Are you sure you want to Delete this item ?')) {
	// 		if(fileType == 'salaryIncrement') {
	// 			$('#salaryIncrement-size-photograph-preview1').remove();
	// 			$('#photo-file-delete').val(1);
	// 		}
	// 	}
	// });
</script>
@endsection