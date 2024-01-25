@extends('layouts.main')
@include('layouts.formstyle')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script> -->
@section('content')
@canany(['create-liability','current-user-create-liability','edit-liability','current-user-edit-liability'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-liability','current-user-create-liability','edit-liability','current-user-edit-liability']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Create Employee Overtime Application Request</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('overtime.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
    <form id="employeeOvertimeForm" name="employeeOvertimeForm" enctype="multipart/form-data" method="POST" action="{{route('overtime.store')}}">
        @csrf
		<div class="row">
			<!-- <div class="col-xxl-2 col-lg-6 col-md-6">
				<span class="error">* </span>
				<label for="request_date" class="col-form-label text-md-end">{{ __('Choose Date') }}</label>
				<input type="date" name="request_date" id="request_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
			</div> -->
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
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="passport_number_div">
                        <center><label for="passport_number" class="col-form-label text-md-end"><strong>{{ __('Passport Number') }}</strong></label></center>
                        <center><span id="passport_number"></span></center>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="joining_date_div">
                        <center><label for="joining_date" class="col-form-label text-md-end"><strong>{{ __('Joining Date') }}</strong></label></center>
                        <center><span id="joining_date"></span></center>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="designation_div">
                        <center><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
                        <center><span id="designation"></span></center>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="department_div">
                        <center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
                        <center><span id="department"></span></center>
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
				<h4 class="card-title">Overtime Information</h4>
			</div>
			<div class="card-body">
                <div class="col-md-12 form_field_outer p-0" id="child">
                </div>
                <div class="col-xxl-12 col-lg-12 col-md-12">
                    <a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                </div>
            </div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
<div class="overlay"></div>
@endif
@endcanany
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	var data = {!! json_encode($employees) !!};
	$(document).ready(function () {
        addChild();
			$("#passport_number_div").hide();
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
                $("#passport_number_div").hide();
                $("#joining_date_div").hide();
                $("#designation_div").hide();
                $("#department_div").hide();
                $("#location_div").hide();
            }
            else {
				document.getElementById('passport_number').textContent = '';
				document.getElementById('joining_date').textContent = '';
				document.getElementById('designation').textContent = '';
				document.getElementById('department').textContent = '';
				document.getElementById('location').textContent = '';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].id == Number(selectedEmpId)) {
						if(data[i].emp_profile.passport_number != null) {
							document.getElementById('passport_number').textContent=data[i].emp_profile.passport_number;
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
                $("#passport_number_div").show();
                $("#joining_date_div").show();
                $("#designation_div").show();
                $("#department_div").show();
                $("#location_div").show();
            }          
        });	
        $("body").on("click",".add_new_frm_field_btn", function () { 
            addChild();
        }); 
        $("body").on("click", ".remove_node_btn_frm_field", function () {
            var count = $(".form_field_outer").find(".form_field_outer_row").length; 
            if(count > 1) {
                $(this).closest(".form_field_outer_row").remove();
            }
            else {
                alert('Atleast One Row Required');
            }
        });
        function addChild() {
            var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; 
            $(".form_field_outer").append(`
                <div class="row form_field_outer_row" id="${index}">														
                    <div class="col-xxl-2 col-lg-2 col-md-2">
                        <label for="start_datetime" class="col-form-label text-md-end">{{ __('Overtime Start Date & Time') }}</label>
                        <input id="start_datetime_${index}" type="datetime-local" class="form-control widthinput @error('start_datetime') is-invalid @enderror" 
                        name="overtime[${index}][start_datetime]" value="" autocomplete="start_datetime" autofocus data-index="${index}" onchange="maxDate(${index})">
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2">
                        <label for="end_datetime" class="col-form-label text-md-end">{{ __('Overtime End Date & Time') }}</label>
                        <input id="end_datetime_${index}" type="datetime-local" class="form-control widthinput @error('end_datetime') is-invalid @enderror" 
                        name="overtime[${index}][end_datetime]" value="" autocomplete="end_datetime" autofocus data-index="${index}">
                    </div>
                    <div class="col-xxl-7 col-lg-7 col-md-7">
                        <label for="remarks" class="col-form-label text-md-end">{{ __('Remarks') }}</label>
                        <input id="remarks_${index}" type="text" class="remarks form-control widthinput @error('remarks') is-invalid @enderror" 
                        name="overtime[${index}][remarks]" placeholder="Please Enter Remarks" value="" autocomplete="remarks" autofocus data-index="${index}">
                    </div>
                    <input type="hidden" value="" id="max_${index}">
                    <input type="hidden" value="" id="min_${index}">
                    <div class="col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                        <a class="btn_round remove_node_btn_frm_field" title="Remove Row">
                        <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            `);
            $("#start_datetime_"+index).rules('add', {
                required:true,
            });
            $("#end_datetime_"+index).rules('add', {
                required:true,
                // max: function(){ return $("#start_datetime_"+index).val();},
            });
        }	
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
	$('#employeeOvertimeForm').validate({ // initialize the plugin
        rules: {
			employee_id: {
                required: true,
            },
        },
    });
    function maxDate(index) {
        var date = new Date(this.valueAsNumber);
  date.setDate(date.getDate() + 1);
  $("#max_"_index)[0].valueAsNumber = +date;
  
  console.log(new Date(this.value)) // retrieving as data
        // $("#max_"_index).val();
    }
</script>
@endsection