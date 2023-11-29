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
            font-size: smaller !important;
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

    <form id="employeeJobDescriptionForm" name="employeeJobDescriptionForm" enctype="multipart/form-data" method="POST" action="{{route('employee-hiring-job-description.store-or-update',$jobDescriptionId)}}">

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

                    <!-- ID Number Section -->

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 dropdown-section-div">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-4 col-md-6 col-sm-6 col-12 job-description-lable-name">
                                <label for="sl_no" class="form-label heading-name"><span class="error">* </span>{{ __('Sl. Number:') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="dropdown-option-div">
                                    <select name="sl_no" id="sl_number" class="form-control widthinput" multiple="true" autofocus>
                                        @foreach($allHiringRequests as $hiringRequests)
                                        <option value="{{$hiringRequests->id}}">{{$hiringRequests->id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- </div> -->
                    <br />
                    <!-- <div class="row"> -->

                    <!-- Job Title Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 title-section-div">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-4 col-md-6 col-sm-6 col-12 job-description-lable-name">

                                <label for="job_title" class="col-form-label widthinput heading-name"><span class="error">* </span>Job Title:</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-6 col-sm-6 col-12 ">
                                <input type="text" class="form-control " name="job_title" id="job_title" value="" readonly>
                            </div>
                        </div>
                    </div>

                    @foreach($allHiringRequests as $a)
                    <!-- JobTitle :: {{$a->questionnaire->designation->name ?? ''}} -->
                    <!-- Dep Name :: {{$a->questionnaire->department->name ?? ''}}, -->
                    <!-- Work Location :: {{$a->questionnaire->workLocation->name ?? ''}} -->
                    <!-- Dep Head :: {{$a->department_head_name ?? ''}} -->
                    @endforeach

                    <!-- Department Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 dep-section-div">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-4 col-md-6 col-sm-6 col-12 job-description-lable-name">
                                <label for="department_id" class="col-form-label widthinput heading-name"><span class="error">* </span>Department:</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-6 col-sm-6 col-12">
                                <input type="text" class="form-control " name="department_id" id="department_id" readonly>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- <div class="row location-reporting-div"> -->
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

                    <!-- Reporting Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 reporting-section-div">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-4 col-md-6 col-sm-6 col-12 job-description-lable-name">
                                <label for="reporting_to" class="col-form-label widthinput heading-name"><span class="error">* </span>Reporting To:</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-6 col-sm-6 col-12">
                                <input type="text" class="form-control " name="reporting_to" id="reporting_to" readonly>
                            </div>
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

<script>
    $(document).ready(function() {

        function updateFields(selectedRequest, selectedDropdown) {
            if (selectedRequest) {

                $('#uuid_display').text(selectedRequest.uuid);
                $('#sl_number_display').text(selectedRequest.id);
                $('input[name="job_title"]').val(selectedRequest.questionnaire?.designation?.name);
                $('input[name="department_id"]').val(selectedRequest.questionnaire?.department?.name);
                $('#location_name').val(selectedRequest.questionnaire?.work_location?.id).trigger('change.select2');
                $('input[name="reporting_to"]').val(selectedRequest.department_head_name);

                $('#employeeJobDescriptionForm').validate().resetForm();
                $('#employeeJobDescriptionForm').find('.error').removeClass('select-error other-error');


                if (selectedDropdown === 'uuid_value' && $('#sl_number').val() != selectedRequest.id) {
                    $('#sl_number').val(selectedRequest.id).trigger('change');
                } else if (selectedDropdown === 'sl_number' && $('#uuid_value').val() != selectedRequest.id) {
                    $('#uuid_value').val(selectedRequest.id).trigger('change');
                }
            } else {
                $('#uuid_display').text('');
                $('#sl_number_display').text('');
                $('input[name="job_title"]').val('');
                $('input[name="department_id"]').val('');
                $('#location_name').val('');
                $('input[name="reporting_to"]').val('');
            }
        }

        function getLocationName(locationId) {
            var masterLocations = <?php echo json_encode($masterOfficeLocations); ?>;
            var location = masterLocations.find(function(masterLocation) {
                return masterLocation.id == locationId;
            });

            return location ? location.name : '';
        }


        $('#sl_number').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Sl Number",
        });
        $('#uuid_value').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose uuid",
        });
        $('#location_name').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Location",
        });

        $('#uuid_value').on('change', function() {

            var selectedUUID = $(this).val();

            if (selectedUUID !== null && selectedUUID !== undefined) {
                var hiringRequest = <?php echo json_encode($allHiringRequests); ?>;
                var selectedRequest = hiringRequest.find(function(request) {
                    return request.id == selectedUUID;
                });

                updateFields(selectedRequest, 'uuid_value');
            } else {
                console.log("Else in uuid");
                updateFields(null, 'uuid_value');
            }
        });

        $('#sl_number').on('change', function() {
            var selectedSlNumber = $(this).val();

            if (selectedSlNumber !== null && selectedSlNumber !== undefined) {
                var hiringRequest = <?php echo json_encode($allHiringRequests); ?>;
                var selectedRequest = hiringRequest.find(function(request) {
                    return request.id == selectedSlNumber;
                });

                updateFields(selectedRequest, 'sl_number');
            } else {
                console.log("Else in Sl Number");
                updateFields(null, 'sl_number');
            }

        });

        $('#location_id').on('change', function() {
            var selectedLocationName = $(this).val();

            if (selectedLocationName !== null && selectedLocationName !== undefined) {
                var hiringRequest = <?php echo json_encode($allHiringRequests); ?>;
                var selectedRequest = hiringRequest.find(function(request) {
                    return request.id == selectedLocationName;
                });

                updateFields(selectedRequest, 'location_id');
            } else {
                console.log("Else in Location ");
                updateFields(null, 'location_id');
            }

        });

        $('#uuid_value').on('change', function() {
            var fieldName = $(this).attr('name');
            $('#employeeJobDescriptionForm').validate().element('[name="' + fieldName + '"]');
        });

        $('#sl_number').on('change', function() {
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
                sl_no: {
                    required: true,
                },
                job_title: {
                    required: true,
                },
                department_id: {
                    required: true,
                },
                location_id: {
                    required: true,
                },
                reporting_to: {
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