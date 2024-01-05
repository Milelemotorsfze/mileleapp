@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .select2-container {
        width: 100% !important;
    }

    .contact-info-para {
        padding-left: 10px;
        font-size: 16px;
    }

    ul.list-group {
        display: flex;
        flex-wrap: wrap;
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

    .heading-name {
        margin-left: 10px;
    }

    .emp-liability-lable-name,
    .emp-liability-lable-name-1 {
        /* border: 1px solid; */
        /* color: white; */
        /* background-color: #042849; */
        font-size: 16px;
        display: flex;
        align-items: center;
    }



    .top-margin-input {
        margin-top: 1px !important;
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
    }

    @media (max-width: 991px) {
        .emp-penality-section {
            margin: 20px 0px 0px 0px !important;
        }
    }



    @media (max-width: 767px) {

        .emp-id-section-div,
        .emp-dep-section,
        .emp-joining-date-section,
        .emp-advance-section,
        .emp-penality-section,
        .amountPer-installment-section,
        .emp-sign-section {
            margin: 20px 0px 0px 0px !important;
        }
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
    <h4 class="card-title">Create Employee Leave Form</h4>
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


        <!-- Employee Information Section -->

        <div class="emp-info-main-container">
            <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12 emp-info-section">
                <div class="row">
                    <div class="col-xxl-8 col-lg-8 col-md-7 col-sm-5 col-12 emp-liability-lable-name">

                        <h4 class="emp-liability-heading">1 - Employee Information:</h4>

                    </div>
                </div>
            </div>
            <br />

            <div class="row">

                <div class="col-lg-12 emp-liability-top-info">

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-name-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-8 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Employee Name</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12 top-margin-input">
                                    <input type="text" class="form-control top-margin-input-1" name="empName">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-id-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-8 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Employee ID:</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12 top-margin-input">
                                    <input type="text" class="form-control top-margin-input-1" name="empId">
                                </div>
                            </div>
                        </div>

                    </div>
                    <br />
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 empInfo-date-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12 top-margin-input">
                                    <input type="date" class="form-control top-margin-input-1" name="empInfoDate">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-dep-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-8 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Dept./Location</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="empDepartment">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Passport No.</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Joining Date</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12 top-margin-input">
                                    <input type="date" class="form-control top-margin-input-1" name="empJoiningDate">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <br />
        </div>
        <hr />
        <br />


        <!-- Employee Leave Information Section -->

        <div class="col-lg-12 emp-liability-top-info">
            <div class="col-xxl-8 col-lg-8 col-md-12 col-sm-12 col-12 emp-liability-lable-name">
                <h4 class="emp-liability-heading">2 - Leave Information:</h4>
            </div>
            <br />

            <!-- Type Of Leave  -->

            <div class="typeOfLeave-main-container">
                <div class="typeOfLeave-heading">
                    <span class="error">*</span>
                    <label class="col-form-label text-md-end">
                        <h5>Type of Leave:</h5>
                    </label>
                </div>
                <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item">
                            <input type="checkbox" id="is_leave_annual" name="annual_leave">
                            <label for="annual_leave">Annual</label>
                        </li>
                        <li class="list-group-item">
                            <input type="checkbox" id="is_leave_sick" name="sick_leave">
                            <label for="sick_leave">Sick</label>
                        </li>
                        <li class="list-group-item">
                            <input type="checkbox" id="is_leave_unpaid" name="unpaid_leave">
                            <label for="unpaid_leave">Unpaid</label>
                        </li>
                        <li class="list-group-item">
                            <input type="checkbox" id="is_leave_maternity" name="maternity_paternity_leave">
                            <label for="maternity_paternity_leave">Maternity/Paternity</label>
                        </li>

                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-5 col-8">
                                    <input type="checkbox" id="is_leave_others" name="other_leave_reason">
                                    <label for="other_leave_reason">Others:</label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-7 col-8 other-input-container">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <input type="text" class="form-control" name="otherReason" id="otherReason">
                                    </div>
                                </div>
                            </div>
                        </li>


                    </ul>
                </div>
            </div>
            </br>
            <!-- leave Details  -->

            <div class="leaveDetails-main-container">
                <div class="leaveDetails-heading">
                    <span class="error">*</span>
                    <label class="col-form-label text-md-end">
                        <h5>Leave Details:</h5>
                    </label>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Leave Start Date:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12">
                                <input type="date" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Leave End Date:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12 top-margin-input">
                                <input type="date" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>

                </br>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Total No. of Days</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                </div>

                <br />

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">No. of Paid Days (if any)</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">No. of Unpaid Days (if any)</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12 top-margin-input">
                                <input type="text" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </br>

            <!-- Contact Information   -->

            <div class="leaveDetails-main-container">
                <div class="leaveDetails-heading">
                    <span class="error">*</span>
                    <label class="col-form-label text-md-end">
                        <h5>Contact Information:</h5>
                    </label>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-5 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Address While on leave:</label>
                            </div>
                            <div class="col-xxl-10 col-lg-9 col-md-8 col-sm-7 col-12">
                                <textarea class="form-control top-margin-input-1" name="empLiabilityReason" rows="5" cols="25"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Home Contact No.</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Personal Email</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12 top-margin-input">
                                <input type="email" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>


                <br />
                <br />

                <!-- Contact Information Signature Div  -->
                <div class="row">
                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-10 col-12 ">
                        <p class="contact-info-para">I do confirm that I will report back to duty on the due date as approved by the Management,
                            otherwise the Company will consider me as an absentee as per the Law.</p>
                    </div>

                    <br />

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                            <div class="row">
                                <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date:</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12">
                                    <input type="date" class="form-control top-margin-input-1" name="empPassportNum">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                            <div class="row">
                                <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Applicant's Signature</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12 top-margin-input">
                                    <input type="email" class="form-control top-margin-input-1" name="empJoiningDate">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                </br>


            </div>
        </div>
        <br />

        <hr />
        <br />

        <!-- Human Resource Filling Section -->
        <div class="col-lg-12 emp-liability-top-info">
            <div class="col-xxl-8 col-lg-8 col-md-12 col-sm-12 col-12 emp-liability-lable-name">
                <h4 class="emp-liability-heading">3 - Human Resource (to be filled by the HR):</h4>
            </div>
            <br />

            <div class="leaveDetails-main-container">

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Passport Expiry:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12">
                                <input type="date" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Visa Expiry:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12 top-margin-input">
                                <input type="date" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>


                <br />

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Advance/Loan Balance:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Others:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-4 col-sm-5 col-12 top-margin-input">
                                <input type="email" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>


                <br />

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-5 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Remarks:</label>
                            </div>
                            <div class="col-xxl-10 col-lg-9 col-md-8 col-sm-7 col-12">
                                <textarea class="form-control top-margin-input-1" name="empLiabilityReason" rows="5" cols="25"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">HR Manager:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Signature:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12 top-margin-input">
                                <input type="text" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12 top-margin-input">
                                <input type="date" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            </div>
        </div>
        <br />

        <hr />
        <br />


        <!-- Approval (to be filled by Manager/Department Head) Section -->

        <div class="col-lg-12 emp-liability-top-info">
            <div class="col-xxl-8 col-lg-8 col-md-12 col-sm-12 col-12 emp-liability-lable-name">
                <h4 class="emp-liability-heading">4 - Approval (to be filled by Manager/Department Head) :</h4>
            </div>
            <br />

            <div class="leaveDetails-main-container">

                <div class="typeOfLeave-main-container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                            <div class="row">
                                <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Designation:</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-10 col-10">
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item">
                                <input type="checkbox" id="is_leave_annual" name="annual_leave">
                                <label for="annual_leave">Supervisor</label>
                            </li>
                            <li class="list-group-item">
                                <input type="checkbox" id="is_leave_sick" name="sick_leave">
                                <label for="sick_leave">Manager</label>
                            </li>

                        </ul>
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-10 col-10">
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item">
                                <input type="checkbox" id="is_leave_unpaid" name="unpaid_leave">
                                <label for="unpaid_leave">Approved</label>
                            </li>
                            <li class="list-group-item">
                                <input type="checkbox" id="is_leave_maternity" name="maternity_paternity_leave">
                                <label for="maternity_paternity_leave">Not Approved</label>
                            </li>
                        </ul>
                    </div>
                </div>
                </br>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">To be Replaced By:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                </div>


                <br />

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-2 col-lg-3 col-md-3 col-sm-5 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Remarks:</label>
                            </div>
                            <div class="col-xxl-10 col-lg-9 col-md-9 col-sm-7 col-12">
                                <textarea class="form-control top-margin-input-1" name="empLiabilityReason" rows="5" cols="25"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Name:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                </div>


                <br />

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Signature:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12 top-margin-input">
                                <input type="date" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            </div>
        </div>
        <br />

        <hr />
        <br />

        <!-- Approval (to be filled by Division Head)) Section -->

        <div class="col-lg-12 emp-liability-top-info">
            <div class="col-xxl-8 col-lg-8 col-md-12 col-sm-12 col-12 emp-liability-lable-name">
                <h4 class="emp-liability-heading">5 - Approval (to be filled by Division Head) :</h4>
            </div>
            <br />

            <div class="leaveDetails-main-container">

                <div class="typeOfLeave-main-container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                            <div class="row">
                                <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Designation:</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-10 col-10">
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item">
                                <input type="checkbox" id="is_leave_unpaid" name="unpaid_leave">
                                <label for="unpaid_leave">Approved</label>
                            </li>
                            <li class="list-group-item">
                                <input type="checkbox" id="is_leave_maternity" name="maternity_paternity_leave">
                                <label for="maternity_paternity_leave">Not Approved</label>
                            </li>
                        </ul>
                    </div>
                </div>
                </br>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">To be Replaced By:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                </div>


                <br />

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-2 col-lg-3 col-md-3 col-sm-5 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Remarks:</label>
                            </div>
                            <div class="col-xxl-10 col-lg-9 col-md-9 col-sm-7 col-12">
                                <textarea class="form-control top-margin-input-1" name="empLiabilityReason" rows="5" cols="25"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Name:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                </div>


                <br />

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-passport-num-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Signature:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPassportNum">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-joining-date-section">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-7 col-12 emp-liability-lable-name">
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-5 col-12 top-margin-input">
                                <input type="date" class="form-control top-margin-input-1" name="empJoiningDate">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            </div>
        </div>
        <br />

        <hr />
        <br />


    </form>

</div>
</br>
<div class="col-lg-12 col-md-12 col-sm-12 col-12">
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

</script>
@endpush