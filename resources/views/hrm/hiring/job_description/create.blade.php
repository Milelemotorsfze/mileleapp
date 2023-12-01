@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .select-error,
    .other-error {
        color: red;
    }

    .btn.btn-success.btncenter {
        width: 10%;
    }

    .job-description-label-div {
        margin-top: 20px !important;
    }

    .col-form-label {
        padding-bottom: 0px;
    }

    .dropdown-section-div,
    .dep-section-div,
    .title-section-div,
    .reporting-section-div {
        margin-top: 20px !important;
    }

    .job-description-lable-name,
    .job-description-lable-name-1 {
        /* border: 1px solid; */
        /* color: white; */
        /* background-color: #042849; */
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .job-description-textfield-lable,
    .job-description-text-value

    /* .reporting-section-div, .dep-section-div, .title-section-div */
        {
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }



    /* @media (min-width: 767px) {
    .location-reporting-dic{
        margin-top: 15px;
    }
} */

    @media (max-width: 767px) {
        /* .dropdown-section-div {
            padding-top: 20px;
        } */
    }

    .error {
        color: #FF0000;
    }

    .error-text {
        color: #FF0000;
    }

    @media (max-width: 425px) {
        .heading-name {
            font-size: 14px !important;
        }

        .job-description-text-value {
            font-size: 13px !important;
        }
    }
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">Create Job Description Form</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="col-lg-12">
        <div id="flashMessage"></div>
    </div>
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
    <!-- {!! Form::open(array('route' => 'calls.store','method'=>'POST', 'id' => 'calls')) !!} -->
    <!-- <div>JD iD: {{ $currentHiringRequest }} </div> -->

    <form id="employeeJobDescriptionForm" name="employeeJobDescriptionForm" enctype="multipart/form-data" method="POST" action="{{route('employee-hiring-job-description.store-or-update', $jobDescriptionId )}}">

        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                <div class="row">
                    <div class="col-xxl-5 col-lg-4 col-md-6 col-sm-6 col-12 job-description-lable-name">
                        <label for="request_date" class="col-form-label text-md-end"><span class="error">* </span> {{ __('Choose Date') }}</label>
                    </div>
                    <div class="col-xxl-7 col-lg-6 col-md-6 col-sm-6 col-12">
                        <input type="date" name="request_date" id="request_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                <p><span style="float:right;" class="error">* Required Field</span></p>
            </div>
        </div>

        <br />

        <div class="row">

            <div class="col-lg-12 job-desc-top-info">
                <div class="row">
                    <!-- UUID Section -->

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 dropdown-section-div">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-4 col-md-6 col-sm-6 col-12 job-description-lable-name">
                                <label for="uuid" class="form-label heading-name"><span class="error">* </span>{{ __('UUID Number:') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="dropdown-option-div">
                                    <select name="uuid" id="uuid_value" class="form-control widthinput" multiple="true" autofocus>
                                        @foreach($allHiringRequests as $hiringRequests)
                                        <option value="{{$hiringRequests->id}}">{{$hiringRequests->uuid}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Location Section -->

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 dropdown-section-div">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-4 col-md-6 col-sm-6 col-12 job-description-lable-name">
                                <label for="location_id" class="col-form-label widthinput heading-name"><span class="error">* </span>Location:</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-6 col-sm-6 col-12 ">
                                <div class="dropdown-option-div">
                                    <select name="location_id" id="location_name" class="form-control widthinput" multiple="true" autofocus>
                                        @foreach($masterOfficeLocations as $masterOfficeLocation)
                                        <option value="{{$masterOfficeLocation->id}}">{{$masterOfficeLocation->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <!-- Job Title Section -->
                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 title-section-div">
                        <div class="col-12 job-description-textfield-lable">

                            <label for="job_title" class="col-form-label widthinput heading-name">Job Title</label>
                        </div>
                        <div class="col-12 job-description-text-value">
                            <div name="job_title" class="job-title"></div>
                        </div>
                    </div>

                    @foreach($allHiringRequests as $a)
                    <!-- JobTitle :: {{$a->questionnaire->designation->name ?? ''}} -->
                    <!-- Dep Name :: {{$a->questionnaire->department->name ?? ''}}, -->
                    <!-- Work Location :: {{$a->questionnaire->workLocation->name ?? ''}} -->
                    <!-- Dep Head :: {{$a->department_head_name ?? ''}} -->
                    @endforeach

                    <!-- Department Section -->
                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 dep-section-div">
                        <div class="col-12 job-description-textfield-lable">
                            <label for="department_id" class="col-form-label widthinput heading-name">Department</label>
                        </div>
                        <div class="col-12 job-description-text-value">
                            <div name="department_id" class="department-id"></div>
                        </div>
                    </div>
                    <!-- </div> -->

                    <!-- Reporting Section -->
                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 reporting-section-div">
                        <div class="col-12 job-description-textfield-lable">
                            <label for="reporting_to" class="col-form-label widthinput heading-name">Reporting To</label>
                        </div>
                        <div class="job-description-text-value">
                            <div name="reporting_to" class="reporting-to"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Section of Job Description Form -->

            <div class="job-description-label-div">
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="job_purpose" class="form-label heading-name"><span class="error">* </span>Job Purpose</label>
                </div>
                <div class="col-lg-12  ">
                    <textarea cols="25" rows="3" class="form-control" name="job_purpose" placeholder="Job Purpose"></textarea>
                </div>
            </div>

            <div class="job-description-label-div">
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="duties_and_responsibilities" class="form-label heading-name"><span class="error">* </span>Duties and Responsibilities (Generic) of the position </label>
                </div>
                <div class="col-lg-12  ">
                    <textarea cols="25" rows="7" class="form-control" name="duties_and_responsibilities" placeholder="Duties and Responsibilities"></textarea>
                </div>
            </div>


            <div class="job-description-label-div">
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="skills_required" class="form-label heading-name"><span class="error">* </span>Skills required to fulfil the position </label>
                </div>
                <div class="col-lg-12  ">
                    <textarea cols="25" rows="7" class="form-control" name="skills_required" placeholder="Required Skills"></textarea>
                </div>
            </div>


            <div class="job-description-label-div">
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="position_qualification" class="form-label heading-name"><span class="error">* </span>Position Qualification (Academic & Professional) </label>
                </div>
                <div class="col-lg-12  ">
                    <textarea cols="25" rows="7" class="form-control" name="position_qualification" placeholder="Position Qualification"></textarea>
                </div>
            </div>

            <!-- <div >
                <div class="col-lg-12  job-description-lable-name-1">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label heading-name">Approvals: </label>
                </div>
                <div class="row ">
                    <div class="col-lg-6 col-md-6 col-6 manager-1">
                        <input class="form-control job-desc-signature " name="depmanagersign" placeholder=""></input>
                    </div>
                    <div class="col-lg-6 col-md-6 col-6 manager-2">
                        <input class="form-control job-desc-signature " name="hrmanagersign" placeholder=""></input>
                    </div>
                </div>

                <div class="row job-desc-signature-name">
                    <div class="col-lg-6 col-md-6 col-6 manager-1">
                        <label for="basicpill-firstname-input" class="form-control" name="depmanager"><b class="approvals-managers">Department Manager</b></label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-6 manager-2">
                        <label for="basicpill-firstname-input" class="form-control" name="hrmanager"><b class="approvals-managers">HR Manager</b></label>
                    </div>
                </div>
            </div> -->

        </div>
        <br />
        <div class="col-lg-12 col-md-12 col-sm-12">
            <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
        </div>

    </form>

</div>

@else
@php
redirect()->route('home')->send();
@endphp
@endif
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
    var data = <?php echo json_encode($allHiringRequests); ?>;
    console.log("Data iiis -----", data)
    var currentHiringRequestValue = <?php echo json_encode($currentHiringRequest); ?>;
    var currentHiringRequestId = currentHiringRequestValue.id;
    var uuidValue = currentHiringRequestValue.uuid
    console.log("Current Data --- ;", uuidValue);

    $(document).ready(function() {

        var selectedUUID = $('#uuid_value').val();
        updateFieldsBasedOnUUID(selectedUUID);



        $('#location_name').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Location",
        });
        $('#uuid_value').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose uuid",
        });

        // Execute function when there is data in currentHiringRequestId
        function setUUIDValueOnReload() {
        if (currentHiringRequestId) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].id == currentHiringRequestId) {
                    $('#uuid_value').val([data[i].id]).trigger('change');
                    $('.job-title').text(data[i].questionnaire.designation.name || '');
                    $('.department-id').text(data[i].questionnaire.department.name || '');
                    $('.reporting-to').text(data[i].department_head_name || '');

                    var workLocationId = data[i].questionnaire.work_location.id;
                    updateLocationDropdown(workLocationId);

                    break;
                }
            }
        }
    }
    setUUIDValueOnReload();

        // Execute function when there is change in uuid value

        function updateFieldsBasedOnUUID(selectedUUID) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].id == selectedUUID) {
                    $('.job-title').text(data[i].questionnaire.designation.name || '');
                    $('.department-id').text(data[i].questionnaire.department.name || '');
                    $('.reporting-to').text(data[i].department_head_name || '');

                    var workLocationId = data[i].questionnaire.work_location.id;
                    updateLocationDropdown(workLocationId);
                    break;
                }
            }
        }

        function updateLocationDropdown(locationId) {
            $('#location_name').val([locationId]).trigger('change');
        }

        function clearFields() {
            $('.job-title').text('');
            $('.department-id').text('');
            $('.reporting-to').text('');
            $('#location_name').val(null).trigger('change');
        }

        $('#uuid_value').on('change', function() {
            var selectedUUID = $(this).val();
            console.log("selected value of uuid -------------------- in loop is ", selectedUUID)

            if (!selectedUUID || selectedUUID.length === 0) {
                console.log("In Null uuid")
                clearFields();
            } else {
                updateFieldsBasedOnUUID(selectedUUID);
            }
        });



        $('#uuid_value').on('change', function() {
            var fieldName = $(this).attr('name');
            $('#employeeJobDescriptionForm').validate().element('[name="' + fieldName + '"]');
        });

        $('#location_name').on('change', function() {
            var fieldName = $(this).attr('name');
            $('#employeeJobDescriptionForm').validate().element('[name="' + fieldName + '"]');
        });

        $('#employeeJobDescriptionForm').validate({
            rules: {
                request_date: {
                    required: true,
                },
                uuid: {
                    required: true,
                },
                location_id: {
                    required: true,
                },
                job_purpose: {
                    required: true,
                },
                duties_and_responsibilities: {
                    required: true,
                },
                skills_required: {
                    required: true,
                },
                position_qualification: {
                    required: true,
                },
            },

            errorPlacement: function(error, element) {
                console.log("Error placement function called");

                if (element.is('select') && element.closest('.dropdown-section-div').length > 0) {
                    if (!element.val() || element.val().length === 0) {
                        console.log("Error is here with length", element.val().length);
                        error.addClass('select-error');
                        error.insertAfter(element.closest('.dropdown-option-div'));
                    } else {
                        console.log("No error");
                    }
                } else {
                    error.addClass('other-error');
                    error.insertAfter(element);
                }
            },
        });

    });
</script>


@endpush