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
        .col-lg-12.col-md-12 col-sm-12 {
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
    <h4 class="card-title">@if($currentQuestionnaire->id == '')Create New @else Edit @endif Questionnaire</h4>
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
    @include('hrm.hiring.hiring_request.details')
    <div class="row">
        <p><span style="float:right;" class="error">* Required Field</span></p>
    </div>
    <form action="" method="post" enctype="multipart/form-data">

        <div class="row">
            <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                <div class="row ">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">


                        <label for="sales-options" class="form-label"><span class="error">* </span>Designation:</label>
                        <div class="designation-radio-button">
                            <label>
                                <input type="radio" name="designation-name" id="auto-assign-option" value="auto-assign-0"> Prior
                            </label>
                            <label>
                                <input type="radio" name="designation-name" id="manual-assign-option" value="manual-assign-0"> Current
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                <div class="row ">
                    <div class="col-lg-12  col-md-12 col-sm-12 ">
                        

                        <label for="sales-options" class="form-label"><span class="error">* </span>Hiring Time:</label>
                        <div class="designation-radio-button">
                            <label>
                                <input type="radio" name="hiring-time" id="auto-assign-option" value="auto-assign-1"> Immediate
                            </label>
                            <label>
                                <input type="radio" name="hiring-time" id="manual-assign-option" value="manual-assign-1"> 1 - Month
                            </label>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class=" col-lg-4 col-md-6 col-sm-6">
                    
                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Designation 2 (New Role)</label>
                    <select name="designation-1" id="designation-1" class="form-control widthinput" onchange="showDiv('otherDesignationInputContainer', this)" autofocus>
                        <option value=""></option>
                        <option value="option1">option1</option>
                        <option value="option2">option2</option>
                        <option value="option3">option3</option>
                        <option value="0">other</option>
                    </select>
                </div>

                <!-- New Designation div shown on the right side -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <!-- when the user chooses other, show this other new designation div  -->
                    <div class="otherDesignationInputContainer" id="otherDesignationInputContainer" style="display: none">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Other:</label>
                        <input type="text" placeholder="Other" name="otherDesignation" class="form-control" id="otherDesignationInput">
                    </div>
                </div>
            </div>


            <div class=" col-lg-4 col-md-6 col-sm-6   ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Reporting To</label>
                <select name="designation" id="designation" class="form-control widthinput" autofocus>
                    <option value=""></option>
                    <option value="option11">Management</option>
                    <option value="option22">Team Lead</option>
                </select>
            </div>
            <div class=" col-lg-4 col-md-6 col-sm-6 ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Work Location</label>
                <select name="designation" id="designation" class="form-control widthinput" autofocus>
                    <option value=""></option>
                    <option value="option1">option1</option>
                    <option value="option2">option2</option>
                </select>
            </div>

            <div class=" col-lg-4 col-md-6 col-sm-6 ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Number of Hirings : </label>
                <input type="number" placeholder="Location" name="location" class="form-control" id="locationInput">
            </div>




            <div class=" col-lg-4 col-md-6 col-sm-6 ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Years of Experience : </label>
                <input type="number" placeholder="No. of years" name="location" class="form-control" id="locationInput">
            </div>


            <div class=" col-lg-4 col-md-6 col-sm-6 ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Working Hours:</label>
                <div class="input-group">
                    <input type="number" placeholder="From" name="startTime" class="form-control" id="startTimeInput">
                    <span class="input-group-text">to</span>
                    <input type="number" placeholder="Till" name="endTime" class="form-control" id="endTimeInput">
                </div>
            </div>



            <div class=" col-lg-4 col-md-6 col-sm-6 ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Any Specific Company Experience : </label>
                <input type="number" placeholder="Company Experience" name="location" class="form-control" id="locationInput">
            </div>

        </div>

        <div class="row">
            <div class=" col-lg-4 col-md-6 col-sm-6 ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Any specific industry experience</label>
                <select name="industry-exp" id="industry-exp" class="form-control widthinput" onchange="showDiv('otherSpecificIndustryExpInputContainer', this)" autofocus>
                    <option value=""></option>
                    <option value="Automative">Automative</option>
                    <option value="logistics">logistics</option>
                    <option value="finance">finance</option>
                    <option value="consultancy">consultancy</option>
                    <option value="0">other</option>
                </select>

            </div>

            <!-- Specifiy div shown on the right side -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <!-- when the user chooses other, show this Specify div  -->
                <div class="otherSpecificIndustryExpInputContainer" id="otherSpecificIndustryExpInputContainer" style="display: none">
                    
                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Specify Other:</label>
                    <input type="text" placeholder="Other" name="otherSpecificIndustryExp" class="form-control" id="otherSpecificIndustryExp">
                </div>
            </div>
        </div>


        <div class="row">
            <div class=" col-lg-4 col-md-6 col-sm-6 ">
                
                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Education</label>
                <select name="education" id="designation" class="form-control widthinput" onchange="showDiv('otherEducationInputContainer', this)" autofocus>
                    <option value=""></option>
                    <option value="option1">option1</option>
                    <option value="option2">option2</option>
                    <option value="option3">option3</option>
                    <option value="0">other</option>
                </select>
            </div>

            <!-- Other div shown on the right side -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <!-- when the user chooses other, show this other other div  -->
                <div class="otherEducationInputContainer" id="otherEducationInputContainer" style="display: none">
                    
                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Other:</label>
                    <input type="text" placeholder="Other" name="otherEducation" class="form-control" id="otherEducationInput">
                </div>
            </div>
        </div>


        <br />

        <div class="maindd">
            <div id="row-container">
                <div class="row">

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Salary Range:</label>
                        <div class="input-group">
                            <input type="number" placeholder="Min Salary" name="minSalary" class="form-control" id="minSalaryInput">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="Max Salary" name="maxSalary" class="form-control" id="maxSalaryInput">
                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Visa Type</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option">option</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Nationality</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Age:</label>
                        <div class="input-group">
                            <input type="number" placeholder="From" name="minAge" class="form-control" id="minAgeInput">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="End" name="maxAge" class="form-control" id="maxAgeInput">
                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Additional Language(s):</label>
                        <select name="designation" id="designation" class="form-control widthinput" multiple autofocus>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                            <option value="option3">option3</option>
                            <option value="option4">option4</option>
                        </select>
                    </div>
                </div>

                <div class="row">


                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">
                                

                                <label for="sales-options" class="form-label"><span class="error">* </span>Did he require to travel for work purpose?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="travelling-purpose" id="auto-assign-option" value="auto-assign-3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="travelling-purpose" id="manual-assign-option" value="manual-assign-3"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="sales-options" class="form-label"><span class="error">* </span>Do candidates require multiple industry experience?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="multiple-industry-exp" id="auto-assign-option" value="auto-assign-4"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="multiple-industry-exp" id="manual-assign-option" value="manual-assign-4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">
                                

                                <label for="sales-options" class="form-label"><span class="error">* </span>Team handling experience is required?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="team-handling" id="auto-assign-option" value="auto-assign-5"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="team-handling" id="manual-assign-option" value="manual-assign-5"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12   designation-radio-main-div">
                                

                                <label for="noOfDaysss" class="form-label"><span class="error">* </span>Is shortlisted candidate require to work on trial ?</label>
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
                                
                                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Enter Number of days:</label>
                                <input type="number" placeholder="no. of days" name="numberOfDays" class="form-control" id="numberOfDaysInput">
                            </div>


                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                        <div class="row">
                            <div class="col-lg-12">
                                
                                <label for="sales-options" class="form-label"><span class="error">* </span>Is commission involved along with the salary?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="comission-value" value="auto-assign-yes-3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="comission-value" value="manual-assign3"> No
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <!-- Dropdown Container -->
                                    <div class="amountpercentageDropDownInputContainer" style="display: none;">
                                        
                                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Choose Amount or Percentage</label>
                                        <select name="designation" id="designation" class="form-control widthinput" onchange="showAmountPercentageInput(this)">
                                            <option value=""></option>
                                            <option value="1">Amount</option>
                                            <option value="2">Percentage</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Amount Input Container -->
                                <div class="col-lg-12 col-md-12 col-sm-12 ">
                                    <div class="amountInputContainer" id="amountInputContainer" style="display: none">
                                        
                                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Enter Amount:</label>
                                        <input type="number" placeholder="amount" name="amount" class="form-control" id="amountInput">
                                    </div>

                                    <!-- Percentage Input Container -->
                                    <div class="percentageInputContainer" id="percentageInputContainer" style="display: none">
                                        
                                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Enter percentage:</label>
                                        <input type="number" placeholder="percentage" name="percentage" class="form-control" id="percentageInput">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                </div>


                <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                    <div class="row ">
                        <div class="col-lg-12   designation-radio-main-div">
                            

                            <label for="driving-lisence" class="form-label"><span class="error">* </span>Driving Lisence Required?</label>

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
                <div class=" col-lg-4 col-md-6 col-sm-6 ">

                    <div class="drivingLisenceInputContainer" style="display: none">
                        <div class="row ">
                            <div class="col-lg-6   designation-radio-main-div">
                                

                                <label for="ownCar" class="form-label"><span class="error">* </span>Own Car</label>

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
                                

                                <label for="fuelExpenses" class="form-label"><span class="error">* </span>Fuels Expenses covered by?</label>

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
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Interviewed By:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class=" col-lg-4 col-md-12 col-sm-12 ">

                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Top 3 skills / mandatory work experience : </label>
                        <textarea name="location" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Objectives of job purpose of job posting: </label>
                        <textarea name="location" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Screening Questions: </label>
                        <textarea name="location" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Technical Questions</label>
                        <textarea name="location" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Job description during trial Working</label>
                        <textarea name="location" class="form-control" rows="3" cols="15"></textarea>
                    </div>
                </div>

                <div class="row ">

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Recruitment Source:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Division / Department:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Career level:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">
                                

                                <label for="sales-options" class="form-label"><span class="error">* </span>Experience</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="experience-level" id="auto-assign-option" value="auto-assign3"> Local
                                    </label>
                                    <label>
                                        <input type="radio" name="experience-level" id="manual-assign-option" value="manual-assign4"> International
                                    </label>
                                    <label>
                                        <input type="radio" name="experience-level" id="manual-assign-option" value="manual-assign4"> Home Country
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">
                                

                                <label for="sales-options" class="form-label"><span class="error">* </span>Travel experience?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="travel-exp" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="travel-exp" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Current or Past Employer Size:</label>
                        <div class="input-group">
                            <input type="number" placeholder="From" name="startSize" class="form-control" id="startSizeInput">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="Till" name="endSize" class="form-control" id="endSizeInput">
                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Trial Pay (AED): </label>
                        <input type="number" placeholder="Trial Pay in AED" name="location" class="form-control" id="locationInput">
                    </div>

                </div>

                <div class="row">

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">
                                

                                <label for="sales-options" class="form-label"><span class="error">* </span>Out of Office Visits?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="office-visit" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="office-visit" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">
                                

                                <label for="sales-options" class="form-label"><span class="error">* </span>Remote Work?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="remote-work" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="remote-work" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">
                                

                                <label for="sales-options" class="form-label"><span class="error">* </span>International Business trips required?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="business-trip" id="auto-assign-option" value="auto-assign3"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="business-trip" id="manual-assign-option" value="manual-assign4"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Probation length (months): </label>
                        <input type="number" placeholder="Probation length in months" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Probation Pay (AED): </label>
                        <input type="number" placeholder="Probation Pay in AED" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Incentive, Perks, & Bonus: </label>
                        <input type="number" placeholder="Incentives" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>KPI: </label>
                        <input type="number" placeholder="KPI" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Practical test: </label>
                        <input type="number" placeholder="Practical test" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Trial objectives and Evaluation method: </label>
                        <input type="number" placeholder="Trial objectives and Evaluation method" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Duties & Tasks : </label>
                        <input type="number" placeholder="Duties & Tasks" name="location" class="form-control" id="locationInput">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        
                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Next Career path:</label>
                        <select name="designation" id="designation" class="form-control widthinput" autofocus>
                            <option value=""></option>
                            <option value="option1">option1</option>
                            <option value="option2">option2</option>
                        </select>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-lg-12">
            
            <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Stakeholders for Job Evaluation</label>
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item">
                    <input type="checkbox" id="item1" name="item1">
                    <label for="item1">Internal departments</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="item2" name="item2">
                    <label for="item2">External vendors</label>
                </li>

            </ul>
        </div>
    </form>
</div>
</br>
</br>
<div class="col-lg-12 col-md-12 col-sm-12">
    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
</div>
</br>
@else
@php
redirect()->route('home')->send();
@endphp
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
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

        // $('input[name="sales-option"]').change(function() {
        //     if ($(this).val() === 'auto-assign-yes-2') {
        //         $('.amountPercentageInputContainer').show();
        //     } else {
        //         $('.amountPercentageInputContainer').hide();
        //     }
        // });
        $('input[name="comission-value"]').change(function() {
            if ($(this).val() === 'auto-assign-yes-3') {
                $('.amountpercentageDropDownInputContainer').show();
            } else {
                $('.amountpercentageDropDownInputContainer').hide();
            }
        });

    });
</script>

<script>
    function showDiv(divId, element) {
        document.getElementById(divId).style.display = element.value == 0 ? 'block' : 'none';
    }

    function showAmountPercentageInput(element) {
        var selectedValue = element.value;
        document.getElementById('amountInputContainer').style.display = selectedValue == '1' ? 'block' : 'none';
        document.getElementById('percentageInputContainer').style.display = selectedValue == '2' ? 'block' : 'none';
    }

    function showAmountPercentageInput(element) {
        var selectedValue = element.value;
        document.getElementById('amountInputContainer').style.display = selectedValue == '1' ? 'block' : 'none';
        document.getElementById('percentageInputContainer').style.display = selectedValue == '2' ? 'block' : 'none';
    }
</script>
@endpush