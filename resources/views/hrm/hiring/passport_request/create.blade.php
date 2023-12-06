@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .submit-passport-section-div {
        padding: 10px;
    }

    .other-checklist-div {
        padding: 5px 20px;
    }

    .submit-passport-para {
        padding: 25px 0px 0px 10px;
    }

    li.list-group-item {
        /* padding: 1.75rem 4.25rem; */
        border: none;
    }

    .btn.btn-success.btncenter {
        width: 10%;
    }

    .col-form-label {
        padding-bottom: 0px;
    }

    .form-label[for="basicpill-firstname-input"] {
        margin-top: 12px;
        margin-left: 10px;
    }

    .heading-name,
    .job-desc-top-info {
        margin-left: 10px;
    }

    .passport-request-lable-name,
    .passport-request-lable-name-1 {
        /* border: 1px solid; */
        /* color: white; */
        /* background-color: #042849; */
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .passport-request-lable-name-1 {
        margin-top: 20px;

    }

    .other-input-container {
        padding: 10px 0px 13px 20px;
    }

    .amountpercentageDropDownInputContainer,
    .other-container-div {
        margin-left: 18px;
    }

    .amountpercentageDropDownInputContainer {
        margin-top: 30px;
    }

    input.job-desc-signature {
        padding: 60px 0;
    }

    .job-desc-signature-name {
        display: flex;
        text-align: center;
        justify-items: center;
    }

    .top-margin-input {
        margin-top: 1px !important;
    }

    div.col-lg-6.col-md-6.col-6.manager-1 {
        padding-right: 0px;
    }

    div.col-lg-6.col-md-6.col-6.manager-2 {
        padding-left: 0px;
    }

    .btn.btn-success.btncenter {
        background-color: #28a745;
        color: #fff;
        border: none;
        /* padding: 10px 20px; */
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn.btn-success.btncenter:hover {
        background-color: #0000ff;
        font-size: 17px;
        border-radius: 10px;
    }

    /* Media Query for small screens */
    @media (max-width: 787px) {
        .btn.btn-success.btncenter {
            width: 30%;
        }

        .job-desc-top-info {
            margin-left: 1px;
        }
    }

    @media (max-width: 767px) {

        .emp-id-section-div,
        .mobile-num-section-div,
        .location-section-div,
        .sign-date-section-div {
            margin: 20px 0px 0px 0px !important;
        }

        .amountpercentageDropDownInputContainer {
            margin-left: 1px;
        }

    }


    .error {
        color: #FF0000;
    }

    .error-text {
        color: #FF0000;
    }

    @media (max-width: 425px) {
        .approvals-managers {
            font-size: smaller;
        }

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
    <h4 class="card-title">Create Passport Request Form</h4>
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
    <div class="row">
        <p><span style="float:right;" class="error">* Required Field</span></p>
    </div>
    <form id="employeePassportRequestForm" name="employeePassportRequestForm" enctype="multipart/form-data" method="POST" action="{{route('employee-passport_request.store-or-update', $id)}}">
        @csrf

        <div class="row">

            <div class="col-lg-12 job-desc-top-info">
                <div class="row">
                    <!-- Employee name Section -->
                    <div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
                        <div class="dropdown-option-div">
                            <label for="employee_name" class="form-label heading-name"><span class="error">* </span>{{ __('Employee Name') }}</label>
                            <select name="employee_name" id="employee_name_id" class="form-control widthinput" multiple="true" autofocus>
                                @foreach($masterEmployees as $User)
                                    <option value="{{ $User->id }}">{{ $User->workLocation }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row job-description-details-div" style="display: none;">

                    <!-- Employee ID Section -->
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 title-section-div">
                        <div class="col-12 job-description-textfield-lable">

                            <label for="employee_default_id" class="col-form-label widthinput heading-name"><b>Employee ID</b></label>
                        </div>
                        <div class="col-12 job-description-text-value">
                            <div name="employee_default_id" class="employee-default-id"></div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 dep-section-div">
                        <div class="col-12 job-description-textfield-lable">
                            <label for="emp_designation" class="col-form-label widthinput heading-name"><b>Designation</b></label>
                        </div>
                        <div class="col-12 job-description-text-value">
                            <div name="emp_designation" class="emp-designation"></div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 reporting-section-div">
                        <div class="col-12 job-description-textfield-lable">
                            <label for="emp_mobile_num" class="col-form-label widthinput heading-name"><b>Mobile No.</b></label>
                        </div>
                        <div class="job-description-text-value">
                            <div name="emp_mobile_num" class="emp-mobile-num"></div>
                        </div>
                    </div>

                    <!-- Department Section -->
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 dep-section-div">
                        <div class="col-12 job-description-textfield-lable">
                            <label for="emp_department" class="col-form-label widthinput heading-name"><b>Department</b></label>
                        </div>
                        <div class="col-12 job-description-text-value">
                            <div name="emp_department" class="emp-department"></div>
                        </div>
                    </div>

                    <!-- Location Section -->
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12 reporting-section-div">
                        <div class="col-12 job-description-textfield-lable">
                            <label for="emp_job_location" class="col-form-label widthinput heading-name"><b>Location</b></label>
                        </div>
                        <div class="job-description-text-value">
                            <div name="emp_job_location" class="emp-job-location"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <br />

        <hr />
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="amountpercentageDropDownInputContainer">

                <label for="choose-passport-req" class="form-label"><span class="error">* </span><b>Choose your option for the passport:</b></label>
                <div class="col-lg-5 col-md-10 col-sm-9 col-11">
                    <select name="designation" id="designation" class="form-control widthinput" onchange="showPassportRequestInput(this)">
                        <option value=""></option>
                        <option value="1">Submission of Passport</option>
                        <option value="2">Release of Passport</option>
                    </select>
                </div>
            </div>
        </div>
        <br />


        <div class="col-lg-12">

            <!-- Passport Submission Input Container -->
            <div class="submit-passport-section-div">
                <div class="submitPassportInputContainer" id="submitPassportInputContainer" style="display: none">
                    <div>
                        <h5>Submit of Passport:</h5>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label for="purposes_of_submit" class="form-label"><strong>I, the undersigned,</strong> consent to authorize
                                the Human Resource Department to take possession of my passport for purposes of :</label>
                            <div class="row">
                                <!-- First Row of List Items -->
                                <div class="col-lg-4">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <input type="checkbox" id="purposes_of_submit" name="passport_submit[]" value="purposes_of_submit">
                                            <label for="purposes_of_submit">Safekeeping</label>
                                        </li>
                                        <li class="list-group-item">
                                            <input type="checkbox" id="purposes_of_submit" name="passport_submit[]" value="purposes_of_submit">
                                            <label for="purposes_of_submit">My dealing with Cash</label>
                                        </li>
                                        <li class="list-group-item">
                                            <input type="checkbox" id="purposes_of_submit" name="passport_submit[]" value="purposes_of_submit">
                                            <label for="purposes_of_submit">My dealing with Sensitive Data</label>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-10 col-md-10 col-sm-10 col-12">
                        <p class="submit-passport-para">However, I can withdraw my passport when required by fulfilling the necessary requirements</p>
                    </div>
                </div>

            </div>


            <!-- Passport Release Input Container -->

            @php
            $listItems = [
            'Leave', 'Passport Renewal', 'ATM / Bank', 'Embassy Formalities', 'Driving License', 'Car Registration',
            'Family Visa/Passport Application', 'Visa Applications', 'E-Gate Card'
            ];

            @endphp

            <div class="release-passport-section-div">
                <div class="releasePassportInputContainer" id="releasePassportInputContainer" style="display: none;">
                    <div>
                        <h5>Release of Passport:</h5>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label for="purposes_of_release" class="form-label">
                                <strong>I, the undersigned,</strong> would like to collect my passport for the following purpose :
                            </label>
                            <div class="row">
                                <?php foreach ($listItems as $item) : ?>
                                    <div class="col-lg-4 col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <input type="checkbox" id="purposes_of_release" name="passport_release[]" value="purposes_of_release">
                                                <label for="purposes_of_release"><?= $item; ?></label>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Other Checkbox and Input -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="other-checklist-div">
                                            <div class="d-flex align-items-center" style="flex-wrap: wrap">
                                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 form-check">
                                                    <input type="checkbox" id="release_purpose" name="release_purpose" class="form-check-input" value="release_purpose">
                                                    <label for="release_purpose" class="form-check-label">Other, please specify:</label>
                                                </div>
                                                <div class="other-input-container">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <input type="text" class="form-control" name="release_purpose" id="release_purpose">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <p class="submit-passport-para">However, I can withdraw my passport when required by fulfilling the necessary requirements</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <hr />

        <!-- Signatures Div -->
        <!-- <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="amountpercentageDropDownInputContainer">
                <h5>Signatures</h5>
            </div>
        </div>
        <hr />

        <div class="col-lg-12 job-desc-top-info">
            <div class="row">
                <div class="col-lg-6 col-md-7 col-sm-10 col-12 emp-sign-section-div">
                    <div class="row">
                        <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 passport-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Employee Name</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                            <input type="text" class="form-control top-margin-input-1" name="empName">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 passport-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="date" class="form-control top-margin-input-1" name="empSign">
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row ">
                <div class="col-lg-6 col-md-7 col-sm-10 col-12 reportingManager-signature-section-div">
                    <div class="row">
                        <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 passport-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Reporting Manager Signature</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                            <input type="text" class="form-control top-margin-input-1" name="repManagerSign">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 reportingManager-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="date" class="form-control top-margin-input-1" name="repManagerSignDate">
                        </div>
                    </div>
                </div>
            </div>
            <br />

            <div class="row">

                <div class="col-lg-6 col-md-7 col-sm-10 col-12 divHead-section-div">
                    <div class="row">
                        <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 divHead-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Divison Head Signature</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="text" class="form-control top-margin-input-1" name="divHeadSign">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 divHead-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="date" class="form-control top-margin-input-1" name="divHeadSignDate">
                        </div>
                    </div>
                </div>
            </div>
            <br />

            <div class="row">

                <div class="col-lg-6 col-md-7 col-sm-10 col-12 hrManager-signature-section-div">
                    <div class="row">
                        <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 hrManager-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">HR Manager Signature</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="text" class="form-control top-margin-input-1" name="hrManager">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 hrManager-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="date" class="form-control top-margin-input-1" name="hrManagerSign">
                        </div>
                    </div>
                </div>
            </div>

        </div> -->

        </br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
        </div>

    </form>

</div>
</br>
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
    $(document).ready(function() {

        var data = <?php echo json_encode($masterEmployees); ?>;
        console.log("Passport Request Form User data  -----", data)

        // var selectedUUID = $('#uuid_value').val();
        // updateFieldsBasedOnUUID(selectedUUID);

        $('#employee_name_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose User Name",
        });

        // Execute with changing uuid value 

        // function toggleJobDescriptionDetailsDiv() {
        //     var selectedUUID = $('#uuid_value').val();
        //     console.log("Selected uuid value in hidden shown div is : ", selectedUUID)

        //     if (selectedUUID && selectedUUID.length > 0) {
        //         $('.job-description-details-div').show();
        //     } else {
        //         $('.job-description-details-div').hide();
        //     }
        // }

        // Execute function when there is data in currentHiringRequestId
        // function setUUIDValueOnReload() {
        //     if (currentHiringRequestId) {
        //         for (var i = 0; i < data.length; i++) {
        //             if (data[i].id == currentHiringRequestId) {
        //                 $('#uuid_value').val([data[i].id]).trigger('change');
        //                 $('.job-title').text(data[i].questionnaire.designation.name || '');
        //                 $('.department-id').text(data[i].questionnaire.department.name || '');
        //                 $('.reporting-to').text(data[i].department_head_name || '');

        //                 if (jobDescriptionLocationId) {
        //                     console.log("in JD location value of updated location")
        //                     var workLocationId = jobDescriptionLocationId;
        //                 } else {
        //                     console.log("In old data tbale location value")
        //                     var workLocationId = data[i].questionnaire.work_location.id;

        //                 }
        //                 updateLocationDropdown(workLocationId);

        //                 toggleJobDescriptionDetailsDiv();
        //                 break;
        //             }
        //         }
        //     }
        // }
        // setUUIDValueOnReload();

        // Execute function when there is change in uuid value

        // function updateFieldsBasedOnUUID(selectedUUID) {
        //     for (var i = 0; i < data.length; i++) {
        //         if (data[i].id == selectedUUID) {
        //             $('.job-title').text(data[i].questionnaire.designation.name || '');
        //             $('.department-id').text(data[i].questionnaire.department.name || '');
        //             $('.reporting-to').text(data[i].department_head_name || '');

        //             var workLocationId = data[i].questionnaire.work_location.id;
        //             updateLocationDropdown(workLocationId);
        //             break;
        //         }
        //     }
        // }

        // Update the location from dropdown

        // function clearFields() {
        //     $('.job-title').text('');
        //     $('.department-id').text('');
        //     $('.reporting-to').text('');
        //     $('#location_name').val(null).trigger('change');
        // }

        // $('#uuid_value').on('change', function() {
        //     var selectedUUID = $(this).val();
        //     console.log("selected value of uuid -------------------- in loop is ", selectedUUID)
        //     toggleJobDescriptionDetailsDiv();
        //     var fieldName = $(this).attr('name');
        //     $('#employeePassportRequestForm').validate().element('[name="' + fieldName + '"]');

        //     if (!selectedUUID || selectedUUID.length === 0) {
        //         console.log("In Null uuid")
        //         clearFields();
        //     } else {
        //         updateFieldsBasedOnUUID(selectedUUID);
        //     }
        // });

        $('#employeePassportRequestForm').submit(function(event) {

            console.log("Data to be sent:", $(this).serialize());
            event.preventDefault();
        });

        $('#employeePassportRequestForm').validate({
            rules: {
                employee_name: {
                    required: true,
                },
                purposes_of_submit: {
                    required: true,
                },
                purposes_of_release: {
                    required: true,
                },
                "passport_submit[]": {
                    required: true,
                },
                "passport_release[]": {
                    required: true,
                },
                release_purpose: {
                    required: true,
                }
            },

            errorPlacement: function(error, element) {
                console.log("Error placement function called");

                if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
                    if (!element.val() || element.val().length === 0) {
                        console.log("Error is here with length", element.val().length);
                        error.addClass('select-error');
                        error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
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


<script>
    function showPassportRequestInput(element) {
        var selectedValue = element.value;
        document.getElementById('submitPassportInputContainer').style.display = selectedValue == '1' ? 'block' : 'none';
        document.getElementById('releasePassportInputContainer').style.display = selectedValue == '2' ? 'block' : 'none';
    }
</script>
@endpush