@extends('layouts.main')
@include('layouts.formstyle')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script> -->
@section('content')
@canany(['create-separation-employee-handover','edit-separation-employee-handover'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-separation-employee-handover','edit-separation-employee-handover']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Create Separation Employee Handover Request</h4>
	<a style="float:right;" class="btn btn-sm btn-info" href="{{ route('separation-handover.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
    <form id="separationEmployeeHandoverForm" name="separationEmployeeHandoverForm" enctype="multipart/form-data" method="POST" action="{{route('separation-handover.store')}}">
        @csrf
		<div class="row">
			<div class="col-xxl-12 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>			
		</div>
		<br>
        <div class="card">
			<div class="card-header">
				<h4 class="card-title">Guidelines/Points to consider while completing the Handover Form</h4>
			</div>
			<div class="card-body">
				<div class="row">
                <ul style="padding-left:25px;">
                    <li style="padding-left:10px; font-size:15px;">Project/Task Details: Clearly outline the project or task being handed over.</li>
                    <li style="padding-left:10px; font-size:15px;">Current Status: Provide an overview of the current status, including completed milestones and ongoing activities.</li>
                    <li style="padding-left:10px; font-size:15px;">Pending Work: List any tasks that are in progress or still need to be completed.</li>
                    <li style="padding-left:10px; font-size:15px;">Upcoming Deadlines: Specify any upcoming deadlines or important dates related to the project or tasks.</li>
                    <li style="padding-left:10px; font-size:15px;">Key Contacts: Provide contact information for relevant stakeholders, team members, or clients associated. </li>
                    <li style="padding-left:10px; font-size:15px;">Dependencies: Outline any dependencies, such as required resources, information, or approvals.</li>
                    <li style="padding-left:10px; font-size:15px;">Documentation and Resources: Include links or references to relevant documents, files, or resources that the new person/team might need.</li>
                    <li style="padding-left:10px; font-size:15px;">Issues/Challenges: Highlight any challenges, issues, or risks that the new person/team should be aware of.</li>
                    <li style="padding-left:10px; font-size:15px;">Knowledge Transfer: Summarize essential knowledge or information that is crucial for understanding and executing the tasks.</li>
                    <li style="padding-left:10px; font-size:15px;">Access and Permissions: Specify any access rights, permissions, or credentials needed to carry out the responsibilities.</li>
                    <li style="padding-left:10px; font-size:15px;">Handover Date: Clearly mention the date of the handover for reference.</li>
                    <li style="padding-left:10px; font-size:15px;">Communication Channels: Specify preferred communication channels and any ongoing meetings or collaborations that the new person/team should be aware of.</li>
                    <li style="padding-left:10px; font-size:15px;">Client Information: If the project involves external clients, share relevant client details, expectations, and points of contact.</li>
                    <li style="padding-left:10px; font-size:15px;">Training Needs: Identify any training requirements or areas where the new person/team might need additional skills or knowledge.</li>
                    <li style="padding-left:10px; font-size:15px;">Regulatory Compliance: Note any legal or regulatory requirements that the new person/team should be aware of and adhere to.</li>
                    <li style="padding-left:10px; font-size:15px;">Contingency Contacts: Share contact information for individuals who can offer support or guidance in case of unexpected challenges.</li>
                    <li style="padding-left:10px; font-size:15px;">Previous Learnings: Share insights from past experiences or lessons learned that can guide the new person/team.</li>
                    <li style="padding-left:10px; font-size:15px;">Socialization and Introductions: Introduce the new person/team to relevant stakeholders and team members to facilitate smoother integration.</li>
                </ul>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Separation Employee Details:</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
                        <select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" autofocus>
                            @foreach($employees as $employee)
                                <option id="emp_{{$employee->id}}" value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
					</div>
                    <div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="last_working_date" class="col-form-label text-md-end">{{ __('Last Working Date') }}</label>
                        <input type="date" name="last_working_date" id="last_working_date" class="form-control widthinput" placeholder="Enter Last Working Date"
								 aria-label="measurement" aria-describedby="basic-addon2" value="">
					</div>
                </div>
</br>
                <div class="row">
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="employee_code_div">
                        <center><label for="employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
                        <center><span id="employee_code"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="passport_number_div">
                        <center><label for="passport_number" class="col-form-label text-md-end"><strong>{{ __('Passport Number') }}</strong></label></center>
                        <center><span id="passport_number"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="joining_date_div">
                        <center><label for="joining_date" class="col-form-label text-md-end"><strong>{{ __('Joining Date') }}</strong></label></center>
                        <center><span id="joining_date"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="designation_div">
                        <center><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
                        <center><span id="designation"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="department_div">
                        <center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
                        <center><span id="department"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="location_div">
                        <center><label for="location" class="col-form-label text-md-end"><strong>{{ __('Location') }}</strong></label></center>
                        <center><span id="location"></span></center>
                    </div>                  
                </div>
</br>
                <div class="row">
                    
                    <div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
						<span class="error">* </span>
						<label for="type" class="col-form-label text-md-end">{{ __('Separation Type') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="separation_type" name="separation_type" value="1" id="separation_type_1" />
                                    <label for="separation_type_1">Contract terminated by Employee</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="separation_type" name="separation_type" value="2" id="separation_type_2"/>
                                    <label for="separation_type_2">Contract Terminated by Employer</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="separation_type" name="separation_type" value="3" id="separation_type_3" />
                                    <label for="separation_type_3">Employee Proceeding for Leave</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="separation_type" name="separation_type" value="4" id="separation_type_4" />
                                    <label for="separation_type_4">Other</label>
                                </div>
                            </div>
</br>
                            <textarea rows="3" id="seperation_type_other" class="form-control" name="seperation_type_other" placeholder="Mention here if separation type is other"></textarea>
                        </fieldset>
					</div>
                    <div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
						<span class="error">* </span>
						<label for="type" class="col-form-label text-md-end">{{ __('Replacement') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="replacement" name="replacement" value="1" id="replacement_1" />
                                    <label for="replacement_1">HRF raised by Line Manager</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="replacement" name="replacement" value="2" id="replacement_2"/>
                                    <label for="replacement_2">Position made redundant</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="replacement" name="replacement" value="3" id="replacement_3" />
                                    <label for="replacement_3">Position filled within Team Member</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="replacement" name="replacement" value="4" id="replacement_4" />
                                    <label for="replacement_4">Other</label>
                                </div>
                            </div>
                        </fieldset>
</br>
                        <textarea rows="3" id="replacement_other" class="form-control" name="replacement_other" placeholder="Mention here if replacement is other"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="card" id="takeover_empdiv">
			<div class="card-header">
				<h4 class="card-title">Takeover Employee Details</h4>
			</div>
			<div class="card-body">
                <div class="row">
                    <div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="takeover_employee_id" class="col-form-label text-md-end">{{ __('Takeover Employee Name') }}</label>
                        <select name="takeover_employee_id" id="takeover_employee_id" multiple="true" class="takeover_employee_id form-control widthinput" autofocus>
                            @foreach($employees as $employee)
                                <option id="takeover_emp_{{$employee->id}}" value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
					</div>
                    <div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
						<span class="error">* </span>
						<label for="employment_type" class="col-form-label text-md-end">{{ __('Employment Type') }}</label>
                        <fieldset style="margin-top:5px;" class="radio-div-container">
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="employment_type" name="employment_type" value="new_hire_under_probation" id="new_hire_under_probation" />
                                    <label for="new_hire_under_probation">New Hire (Under Probation)</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="employment_type" name="employment_type" value="existing_staff" id="existing_staff"/>
                                    <label for="existing_staff">Existing staff</label>
                                </div>
                            </div>
                        </fieldset>
					</div>
                </div>
                </br>
                <div class="row">
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="takeover_employee_code_div">
                        <center><label for="takeover_employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
                        <center><span id="takeover_employee_code"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="takeover_passport_number_div">
                        <center><label for="takeover_passport_number" class="col-form-label text-md-end"><strong>{{ __('Passport Number') }}</strong></label></center>
                        <center><span id="takeover_passport_number"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="takeover_joining_date_div">
                        <center><label for="takeover_joining_date" class="col-form-label text-md-end"><strong>{{ __('Joining Date') }}</strong></label></center>
                        <center><span id="takeover_joining_date"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="takeover_designation_div">
                        <center><label for="takeover_designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
                        <center><span id="takeover_designation"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="takeover_department_div">
                        <center><label for="takeover_department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
                        <center><span id="takeover_department"></span></center>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2" id="takeover_location_div">
                        <center><label for="takeover_location" class="col-form-label text-md-end"><strong>{{ __('Location') }}</strong></label></center>
                        <center><span id="takeover_location"></span></center>
                    </div>                  
                </div>
</br>
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
    var oldTakeoverEmp = '';
    var oldSeparationEmp = '';
	$(document).ready(function () {
        $("#employee_code_div").hide();
        $("#passport_number_div").hide();
        $("#joining_date_div").hide();
        $("#designation_div").hide();
        $("#department_div").hide();
        $("#location_div").hide();
        $("#seperation_type_other").hide();
        $("#replacement_other").hide();
        $("#takeover_empdiv").hide();
        $("#takeover_employee_code_div").hide();
        $("#takeover_passport_number_div").hide();
        $("#takeover_joining_date_div").hide();
        $("#takeover_designation_div").hide();
        $("#takeover_department_div").hide();
        $("#takeover_location_div").hide();
        $('#employee_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Employee Name",
        });
        $('#employee_id').on('change', function() {
            var selectedEmpId = $(this).val();
            if(selectedEmpId == '') {
                $("#employee_code_div").hide();
                $("#passport_number_div").hide();
                $("#joining_date_div").hide();
                $("#designation_div").hide();
                $("#department_div").hide();
                $("#location_div").hide();
                if(oldTakeoverEmp != '') {
                    $('#takeover_emp_'+oldTakeoverEmp).prop('disabled', false);
                }               
                oldTakeoverEmp = '';               
            }
            else {
                document.getElementById('employee_code').textContent = '';
				document.getElementById('passport_number').textContent = '';
				document.getElementById('joining_date').textContent = '';
				document.getElementById('designation').textContent = '';
				document.getElementById('department').textContent = '';
				document.getElementById('location').textContent = '';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].id == Number(selectedEmpId)) {
                        if(data[i].emp_profile.employee_code != null) {
							document.getElementById('employee_code').textContent=data[i].emp_profile.employee_code;
						}
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
                $("#employee_code_div").show();
                $("#passport_number_div").show();
                $("#joining_date_div").show();
                $("#designation_div").show();
                $("#department_div").show();
                $("#location_div").show();
                $('#takeover_emp_'+selectedEmpId).prop('disabled', true);
                oldTakeoverEmp = selectedEmpId;
            }          
        });	
        $('#takeover_employee_id').on('change', function() {
            var takeoverEmpId = $(this).val();
            if(takeoverEmpId == '') {
                $("#takeover_employee_code_div").hide();
                $("#takeover_passport_number_div").hide();
                $("#takeover_joining_date_div").hide();
                $("#takeover_designation_div").hide();
                $("#takeover_department_div").hide();
                $("#takeover_location_div").hide();
                if(oldSeparationEmp != '') {
                    $('#emp_'+oldSeparationEmp).prop('disabled', false);
                }               
                oldSeparationEmp = '';   
            }
            else {
                document.getElementById('takeover_employee_code').textContent = '';
				document.getElementById('takeover_passport_number').textContent = '';
				document.getElementById('takeover_joining_date').textContent = '';
				document.getElementById('takeover_designation').textContent = '';
				document.getElementById('takeover_department').textContent = '';
				document.getElementById('takeover_location').textContent = '';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].id == Number(takeoverEmpId)) {
                        if(data[i].emp_profile.employee_code != null) {
							document.getElementById('takeover_employee_code').textContent=data[i].emp_profile.employee_code;
						}
						if(data[i].emp_profile.passport_number != null) {
							document.getElementById('takeover_passport_number').textContent=data[i].emp_profile.passport_number;
						}
						if(data[i].emp_profile.company_joining_date != null) {
							document.getElementById('takeover_joining_date').textContent=data[i].emp_profile.company_joining_date;
						}
						if(data[i].emp_profile.designation != null) {
							document.getElementById('takeover_designation').textContent=data[i].emp_profile.designation.name;
						}
						if(data[i].emp_profile.department != null) {
							document.getElementById('takeover_department').textContent=data[i].emp_profile.department.name;
						}
						if(data[i].emp_profile.location != null) {
							document.getElementById('takeover_location').textContent=data[i].emp_profile.location.name;
						}
                    }
                }
                $("#takeover_employee_code_div").show();
                $("#takeover_passport_number_div").show();
                $("#takeover_joining_date_div").show();
                $("#takeover_designation_div").show();
                $("#takeover_department_div").show();
                $("#takeover_location_div").show();
                $('#emp_'+takeoverEmpId).prop('disabled', true);
                oldTakeoverEmp = takeoverEmpId;
            }          
        });	
        $('.separation_type').click(function () {
	        if ($(this).val() == 4) {
                $("#seperation_type_other").show();
	        } else {
                $("#seperation_type_other").hide();
	        }
	    });
        $('.replacement').click(function () {
	        if ($(this).val() == 4) {
                $("#replacement_other").show();
	        } else {
                $("#replacement_other").hide();
	        }
            if ($(this).val() == 1 || $(this).val() == 3) {
                $("#takeover_empdiv").show();               
                $('#takeover_employee_id').select2({
                    allowClear: true,
                    maximumSelectionLength: 1,
                    placeholder:"Choose Takeover Employee Name",
                });
	        } else {
                $("#takeover_empdiv").hide();
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
            else if (element.is(':radio') && element.closest('.radio-main-div').length > 0) {
                error.addClass('radio-error');
                error.insertAfter(element.closest('.radio-main-div').find('fieldset.radio-div-container').last());
            }
            else {
                error.insertAfter( element );
            }
        }
    });
    jQuery.validator.addMethod("greaterStart", function (value, element, params) {
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();

        if( startDate >= endDate) {
            return false;
        }else{
            return true;
        }
    },'Must be greater than start date.');
    $('#separationEmployeeHandoverForm').validate({ // initialize the plugin
        rules: {
			employee_id: {
                required: true,
            },
            last_working_date: {
                required: true,
                greaterStart: true,
            },
            separation_type: {
                required: true,
            },
            replacement: {
                required: true,
            },
            seperation_type_other: {                       
                required: function(element){
                    if($("#separation_type").val() == 4) {
                        return false;
                    }
                    else {
                        return true;
                    }
                },
            },
            replacement_other: {                       
                required: function(element){
                    if($("#replacement").val() == 4) {
                        return false;
                    }
                    else {
                        return true;
                    }
                },
            },
            takeover_employee_id: {                       
                required: function(element){
                    if($("#replacement").val() == 1 || $("#replacement").val() == 3) {
                        return false;
                    }
                    else {
                        return true;
                    }
                },
            },
            employment_type: {                       
                required: function(element){
                    if($("#replacement").val() == 1 || $("#replacement").val() == 3) {
                        return false;
                    }
                    else {
                        return true;
                    }
                },
            },
        },
    });
    function checkDate() {
        var EmpId = ''; 
        EmpId = $("#employee_id").val();
        alreadyExistStartDate = [];
        alreadyExistEndDate = [];
        document.querySelectorAll('.form_field_outer_row').forEach(function(overtimeDay) {
            var index = '';
            index = overtimeDay.id;
            var startTime = $("#start_datetime_"+index).val();
            var endTime = $("#end_datetime_"+index).val();
            $msg = '';
            hideStartDateError(index, $msg);
            hideEndDateError(index, $msg);
            document.querySelectorAll('.form_field_outer_row').forEach(function(DayIndex) {
                var DayIndexId = '';
                DayIndexId = DayIndex.id;
                if(DayIndexId != index) {
                    if($("#start_datetime_"+DayIndexId).val() != '' && $("#end_datetime_"+DayIndexId).val() != '') {
                        if($("#start_datetime_"+DayIndexId).val() <= startTime && startTime <= $("#end_datetime_"+DayIndexId).val()) {
                            $msg = 'This start datetime is already added'; 
                            showStartDateError(index, $msg);
                            formInputError = true;
                            e.preventDefault();
                        }
                        else if($("#start_datetime_"+DayIndexId).val() <= endTime && endTime <= $("#end_datetime_"+DayIndexId).val()) {
                            $msg = 'This end datetime is already added'; 
                            showEndDateError(index, $msg);
                            formInputError = true;
                            e.preventDefault();
                        }
                    }
                }
            });
            if(startTime != '' && endTime != '' && EmpId != '') {                             
                $.ajax({
                    url:"{{url('checkOvertimeAlreadyExist')}}",
                    type: "POST",
                    data:{
                        startTime: startTime,
                        endTime: endTime,
                        EmpId: EmpId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(data) { 
                        if(data.startTime == 'yes') {
                            $msg = 'This start datetime is already exist in database'; 
                            showStartDateError(index, $msg);
                            alreadyExistStartDate.push(index);
                        }
                        else {
                            const startArrIndex = alreadyExistStartDate.indexOf(index);
                            if (startArrIndex > -1) { 
                                alreadyExistStartDate.splice(startArrIndex, 1); 
                            }
                        }
                        if(data.endTime == 'yes') {
                            $msg = 'This end datetime is already exist in database'; 
                            showEndDateError(index, $msg);
                            alreadyExistEndDate.push(index);
                        }
                        else {
                            const endArrIndex = alreadyExistEndDate.indexOf(index);
                            if (endArrIndex > -1) { 
                                alreadyExistEndDate.splice(endArrIndex, 1); 
                            }
                        }
                    }
                }); 
            }
        });
    }
    function maxDate(index) {
        $msg = '';
        hideStartDateError(index, $msg);
        hideEndDateError(index, $msg);
        var EmpId = ''; 
        EmpId = $("#employee_id").val();
        var startTime = $("#start_datetime_"+index).val();
        var endTime = $("#end_datetime_"+index).val();
        if(startTime != '' && endTime != '' && startTime >= endTime) {
            $msg = 'Must be greater than overtime start date and time.';
            showEndDateError(index, $msg);
        }
        else if(startTime != '' && endTime != '' && startTime < endTime) {
            var oneM = 1000 * 60;
            var sMS = new Date(startTime);
            var eMS = new Date(endTime);
            var timeDifference =  Math.round((eMS.getTime() - sMS.getTime()) / oneM);
            if(timeDifference > 1440) {
                $msg = 'The time difference must be less than or equal to 24 hours.';
                showEndDateError(index, $msg);   
            }
            else {
                document.querySelectorAll('.form_field_outer_row').forEach(function(DayIndex) {
                    var DayIndexId = '';
                    DayIndexId = DayIndex.id;
                    if($("#start_datetime_"+DayIndexId).val() != '' && $("#end_datetime_"+DayIndexId).val() != '' && DayIndexId != index) {
                        if($("#start_datetime_"+DayIndexId).val() <= startTime && startTime <= $("#end_datetime_"+DayIndexId).val()) {
                            $msg = 'This start datetime is already added'; 
                            showStartDateError(index, $msg);
                        }
                        else if($("#start_datetime_"+DayIndexId).val() <= endTime && endTime <= $("#end_datetime_"+DayIndexId).val()) {
                            $msg = 'This end datetime is already added'; 
                            showEndDateError(index, $msg);
                        }
                    }                    
                });
                if(startTime != '' && endTime != '' && EmpId != '') {                             
                    $.ajax({
                        url:"{{url('checkOvertimeAlreadyExist')}}",
                        type: "POST",
                        data:{
                            startTime: startTime,
                            endTime: endTime,
                            EmpId: EmpId,
                            _token: '{{csrf_token()}}'
                        },
                        dataType : 'json',
                        success: function(data) { 
                            if(data.startTime == 'yes') {
                                $msg = 'This start datetime is already exist in database'; 
                                showStartDateError(index, $msg);
                                alreadyExistStartDate.push(index);
                            }
                            else {
                                const startArrIndex = alreadyExistStartDate.indexOf(index);
                                if (startArrIndex > -1) { 
                                    alreadyExistStartDate.splice(startArrIndex, 1); 
                                }
                            }
                            if(data.endTime == 'yes') {
                                $msg = 'This end datetime is already exist in database'; 
                                showEndDateError(index, $msg);
                                alreadyExistEndDate.push(index);
                            }
                            else {
                                const endArrIndex = alreadyExistEndDate.indexOf(index);
                                if (endArrIndex > -1) { 
                                    alreadyExistEndDate.splice(endArrIndex, 1); 
                                }
                            }
                        }
                    }); 
                }
            }
        }
    }
</script>
@endsection