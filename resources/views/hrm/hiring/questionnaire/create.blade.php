@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .select2-container {
        width: 100% !important;
    }

    .designation-radio-button {
        margin-left: 15px;
    }

    .designation-radio-main-div {
        margin-top: 12px !important;
    }

    .form-label[for="basicpill-firstname-input"] {
        margin-top: 12px;
    }

    .btn.btn-success.btncenter {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 20px;
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
    @media (max-width: 767px) {
        .col-lg-12.col-md-12 {
            text-align: center;
        }
    }

    .error {
        color: #FF0000;
    }

    .iti {
        width: 100%;
    }

    label {
        display: inline-block;
        margin-right: 10px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }

    .error-text {
        color: #FF0000;
    }
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">Create Questionnaire Form</h4>
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
    <form action="" method="post" enctype="multipart/form-data">

        <div class="row">
            <div class="col-lg-4   designation-radio-main-div">
                <div class="row ">
                    <div class="col-lg-6  ">
                        <span class="error">* </span>

                        <label for="sales-options" class="form-label">Designation:</label>
                        <div class="designation-radio-button">
                            <label>
                                <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign"> Prior
                            </label>
                            <label>
                                <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign"> Current
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4   designation-radio-main-div">
                <div class="row ">
                    <div class="col-lg-6  ">
                        <span class="error">* </span>

                        <label for="sales-options" class="form-label">Hiring Time:</label>
                        <div class="designation-radio-button">
                            <label>
                                <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Immediate
                            </label>
                            <label>
                                <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> 1 - Month
                            </label>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row">
            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Designation 2 (New Role)</label>
                <select name="designation-1" id="designation-1" class="form-control widthinput" onchange="showDiv('otherDesignationInputContainer', this)" autofocus>
                    <option value=""></option>
                    <option value="option1">option1</option>
                    <option value="option2">option2</option>
                    <option value="option3">option3</option>
                    <option value="0">other</option>
                </select>
            </div>

            <!-- New Designation div shown on the right side -->
            <div class="col-lg-2 col-md-4">
                <!-- when the user chooses other, show this other new designation div  -->
                <div class="otherDesignationInputContainer" id="otherDesignationInputContainer" style="display: none">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Other:</label>
                    <input type="text" placeholder="Other" name="otherDesignation" class="form-control" id="otherDesignationInput">
                </div>
            </div>
        </div>


            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Reporting To</label>
                <select name="designation" id="designation" class="form-control widthinput" autofocus>
                    <option value=""></option>
                    <option value="option11">option11</option>
                    <option value="option22">option22</option>
                    <option value="option33">option33</option>

                </select>
            </div>
            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Work Location</label>
                <select name="designation" id="designation" class="form-control widthinput" autofocus>
                    <option value=""></option>
                    <option value="option1">option1</option>
                    <option value="option2">option2</option>
                </select>
            </div>

            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Number of Hirings : </label>
                <input type="number" placeholder="Location" name="location" class="form-control" id="locationInput">
            </div>




            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Years of Experience : </label>
                <input type="number" placeholder="No. of years" name="location" class="form-control" id="locationInput">
            </div>


            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Working Hours:</label>
                <div class="input-group">
                    <input type="number" placeholder="From" name="startTime" class="form-control" id="startTimeInput">
                    <span class="input-group-text">to</span>
                    <input type="number" placeholder="Till" name="endTime" class="form-control" id="endTimeInput">
                </div>
            </div>


            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Any Specific Company Experience : </label>
                <input type="number" placeholder="Company Experience" name="location" class="form-control" id="locationInput">
            </div>

        </div>

        <div class="row">
            <div class="col-lg-4  ">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Education</label>
                <select name="education" id="designation" class="form-control widthinput" onchange="showDiv('otherCertificatesInputContainer', this)" autofocus>
                    <option value=""></option>
                    <option value="option1">option1</option>
                    <option value="option2">option2</option>
                    <option value="option3">option3</option>
                    <option value="0">other</option>
                </select>
            </div>

            <!-- Certificates div shown on the right side -->
            <div class="col-lg-2 col-md-4">
                <!-- when the user chooses other, show this other certificate div  -->
                <div class="otherCertificatesInputContainer" id="otherCertificatesInputContainer" style="display: none">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Certificates:</label>
                    <input type="text" placeholder="Other" name="otherCertificates" class="form-control" id="otherCertificatesInput">
                </div>
            </div>
        </div>


        <br />

        <div class="maindd">
            <div id="row-container">
                <div class="row">

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Salary Range:</label>
                        <div class="input-group">
                            <input type="number" placeholder="Min Salary" name="minSalary" class="form-control" id="minSalaryInput">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="Max Salary" name="maxSalary" class="form-control" id="maxSalaryInput">
                        </div>
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Visa Type</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option">option</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Nationality</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Age:</label>
                        <div class="input-group">
                            <input type="number" placeholder="From" name="minAge" class="form-control" id="minAgeInput">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="End" name="maxAge" class="form-control" id="maxAgeInput">
                        </div>
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Additional Language(s):</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                            <option value="option3">option3</option>
                            <option value="option4">option4</option>

                        </select>
                    </div>
                </div>

                <div class="row">


                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">Did he require to travel for work purpose?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">Do candidates require multiple industry experience?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">Team handling experience is required?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10   designation-radio-main-div">
                                <span class="error">* </span>

                                <label for="noOfDaysss" class="form-label">Is shortlisted candidate require to work on trial ?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="noOfDays" id="auto-assign-option" value="auto-assign-yes-1"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="noOfDays" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                            <!-- if yes, add input  button to enter number of days -->
                            <div class="numberOfDaysInputContainer" style="display: none">
                                <span class="error">* </span>
                                <label for="basicpill-firstname-input" class="form-label">Enter Number of days:</label>
                                <input type="number" placeholder="no. of days" name="numberOfDays" class="form-control" id="numberOfDaysInput">
                            </div>


                        </div>
                    </div>

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">

                            <div class="col-lg-10   designation-radio-main-div">
                                <div class="row">
                                    <div class="col-lg-12  ">
                                        <span class="error">* </span>
                                        <label for="sales-options" class="form-label">Is commission involved along with the salary?</label>
                                        <div class="designation-radio-button">
                                            <label>
                                                <input type="radio" name="sales-option" value="auto-assign-yes-2"> Yes
                                            </label>
                                            <label>
                                                <input type="radio" name="sales-option" value="manual-assign4"> No
                                            </label>
                                        </div>
                                    </div>
                                    <!-- if yes, add input for amount/percentage -->
                                    <div class="amountPercentageInputContainer" style="display: none">
                                        <span class="error">* </span>
                                        <label for="basicpill-firstname-input" class="form-label">Enter Amount or Percentage:</label>
                                        <input type="number" placeholder="amount" name="amountPercentage" class="form-control" id="amountPercentageInput">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>


                <div class="col-lg-4   designation-radio-main-div">
                    <div class="row ">
                        <div class="col-lg-10   designation-radio-main-div">
                            <span class="error">* </span>

                            <label for="driving-lisence" class="form-label">Driving Lisence Required?</label>

                            <div class="designation-radio-button">
                                <label>
                                    <input type="radio" name="driving-lisence" id="auto-assign-option" value="auto-assign-yes-0"> Yes
                                </label>
                                <label>
                                    <input type="radio" name="driving-lisence" id="manual-assign-option" value="manual-assign3"> No
                                </label>
                            </div>
                        </div>

                        <!-- if yes, add radio button for: Own car, Expenses done by ? own or Company -->
                        
                    </div>
                </div>
                <div class="col-lg-4  ">

                <div class="drivingLisenceInputContainer" style="display: none">
                            <div class="row ">
                                <div class="col-lg-6   designation-radio-main-div">
                                    <span class="error">* </span>

                                    <label for="ownCar" class="form-label">Own Car</label>

                                    <div class="designation-radio-button">
                                        <label>
                                            <input type="radio" name="own-car" id="auto-assign-option" value="auto-assign-yes"> Yes
                                        </label>
                                        <label>
                                            <input type="radio" name="own-car" id="manual-assign-option" value="manual-assign3"> No
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-6   designation-radio-main-div">
                                    <span class="error">* </span>

                                    <label for="fuelExpenses" class="form-label">Fuels Expenses covered by?</label>

                                    <div class="designation-radio-button">
                                        <label>
                                            <input type="radio" name="fuel-expenses" id="auto-assign-option" value="auto-assign-yes"> Company
                                        </label>
                                        <label>
                                            <input type="radio" name="fuel-expenses" id="manual-assign-option" value="manual-assign3"> Own
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                <div class="row">
                    <div class="col-lg-4  ">

                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Top 3 skills / mandatory work experience : </label>
                        <input type="text" placeholder="Skills / Experience" name="location" class="form-control" id="locationInput">
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Interviewed By:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Objectives of job purpose of job posting: </label>
                        <input type="text" placeholder="Objectives of job posting" name="location" class="form-control" id="locationInput">
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Screening Questions: </label>
                        <input type="text" placeholder="Screening Questions" name="location" class="form-control" id="locationInput">
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Technical Questions</label>
                        <input type="text" placeholder="Technical Questions" name="location" class="form-control" id="locationInput">
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Job description during trial Working</label>
                        <input type="text" placeholder="Roles & Responsibilities" name="location" class="form-control" id="locationInput">
                    </div>


                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Recruitment Source:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Division / Department:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Career level:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">Experience</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Local
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> International
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> Home Country
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">Travel experience?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Current or Past Employer Size:</label>
                        <div class="input-group">
                            <input type="number" placeholder="From" name="startSize" class="form-control" id="startSizeInput">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="Till" name="endSize" class="form-control" id="endSizeInput">
                        </div>
                    </div>

                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Trial Pay (AED): </label>
                        <input type="number" placeholder="Trial Pay in AED" name="location" class="form-control" id="locationInput">
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">Out of Office Visits?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">Remote Work?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-4   designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-10  ">
                                <span class="error">* </span>

                                <label for="sales-options" class="form-label">International Business trips required?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Probation length (months): </label>
                        <input type="number" placeholder="Probation length in months" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Probation Pay (AED): </label>
                        <input type="number" placeholder="Probation Pay in AED" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Incentive, Perks, & Bonus: </label>
                        <input type="number" placeholder="Incentives" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">KPI: </label>
                        <input type="number" placeholder="KPI" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Practical test: </label>
                        <input type="number" placeholder="Practical test" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Trial objectives and Evaluation method: </label>
                        <input type="number" placeholder="Trial objectives and Evaluation method" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Duties & Tasks : </label>
                        <input type="number" placeholder="Duties & Tasks" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class="col-lg-4  ">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Next Career path:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-lg-4">
    <span class="error">* </span>
    <label for="basicpill-firstname-input" class="form-label">Stakeholders for Job Evaluation</label>
    <ul class="list-group list-group-horizontal">
        <li class="list-group-item">
            <input type="checkbox" id="item1" name="item1">
            <label for="item1">Item 1</label>
        </li>
        <li class="list-group-item">
            <input type="checkbox" id="item2" name="item2">
            <label for="item2">Item 2</label>
        </li>
        <li class="list-group-item">
            <input type="checkbox" id="item3" name="item3">
            <label for="item3">Item 3</label>
        </li>
    </ul>
</div>

</div>
</br>
</br>
<div class="col-lg-12 col-md-12">
    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
</div>
</br>
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
        // Show/hide amountPercentageInputContainer based on radio button selection
        $('input[name="driving-lisence"]').change(function() {
            if ($(this).val() === 'auto-assign-yes-0') {
                $('.drivingLisenceInputContainer').show();
            } else {
                $('.drivingLisenceInputContainer').hide();
            }
        });

        $('input[name="noOfDays"]').change(function() {
            if ($(this).val() === 'auto-assign-yes-1') {
                $('.numberOfDaysInputContainer').show();
            } else {
                $('.numberOfDaysInputContainer').hide();
            }
        });

        $('input[name="sales-option"]').change(function() {
            if ($(this).val() === 'auto-assign-yes-2') {
                $('.amountPercentageInputContainer').show();
            } else {
                $('.amountPercentageInputContainer').hide();
            }
        });
    });
</script>

<script>
    function showDiv(divId, element) {
        document.getElementById(divId).style.display = element.value == 0 ? 'block' : 'none';
    }
</script>
@endpush