@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .select2-container {
        width: 100% !important;
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

    .emp-liability-heading {
        font-size: 16px;
    }

    @media (max-width: 767px) {

        .emp-id-section-div,
        .emp-dep-section,
        .emp-jobtitle-section,
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
    <form action="" method="post" enctype="multipart/form-data">


        <!-- Employee Information Section -->

        <div class="emp-info-main-container">
            <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12 emp-info-section">
                <div class="row">
                    <div class="col-xxl-8 col-lg-8 col-md-7 col-sm-5 col-12 emp-liability-lable-name">

                        <h4 class="emp-liability-heading">Employee Information:</h4>

                    </div>
                    <!-- <div class="col-xxl-2 col-lg-2 col-md-3 col-sm-4 col-12 emp-liability-lable-name">
                        <span class="error">*</span>
                        <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Employee ID:</label>
                    </div>
                    <div class="col-xxl-2 col-lg-2 col-md-2 col-sm-3 col-12 top-margin-input">
                        <input type="text" class="form-control top-margin-input-1" name="empId" placeholder="">
                    </div> -->
                </div>
            </div>
            <br />

            <div class="row">

                <div class="col-lg-12 emp-liability-top-info">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-id-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-8 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Employee ID:</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12 top-margin-input">
                                    <input type="text" class="form-control top-margin-input-1" name="empId" placeholder="Employee ID here:">
                                </div>
                            </div>
                        </div>

                    </div>
                    <br />
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
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 empInfo-date-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12 top-margin-input">
                                    <input type="text" class="form-control top-margin-input-1" name="empInfoDate">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-jobtitle-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Job Title</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-6 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="empJobTitle">
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
                                    <input type="text" class="form-control top-margin-input-1" name="empJoiningDate">
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


        <!-- Employee Liability Type Section -->
        <div class="liability-type-main-container">

            <div class="col-lg-12 emp-liability-top-info">
                <div class="col-xxl-8 col-lg-8 col-md-12 col-sm-12 col-12 emp-liability-lable-name">
                    <h4 class="emp-liability-heading">Liability Type:</h4>
                </div>
                <br />

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 emp-loan-section">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-5 col-sm-5 col-8 emp-liability-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Loan</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                                <input type="text" class="form-control top-margin-input-1" name="empLoan">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 emp-advance-section">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-5 col-sm-5 col-8 emp-liability-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Advances</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empAdvance">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-10 col-12 emp-penality-section">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-5 col-sm-5 col-8 emp-liability-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Penality/Fine</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empPenality">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />

        <hr />
        <br />

        <!-- Liability Details Section -->

        <div class="liability-details-main-container">
            <div class="col-xxl-12 col-lg-12 col-md-6 col-sm-10 col-12 liability-details-section">
                <div class="row">
                    <div class="col-xxl-8 col-lg-7 col-md-12 col-sm-12 col-12 emp-liability-lable-name">

                        <h4 class="emp-liability-heading">Liability Details:</h4>

                    </div>
                </div>
            </div>
            <br />

            <div class="row">

                <div class="col-lg-12 emp-liability-top-info">

                    <div class="row">
                        <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-10 col-12 total-liability-amount-section">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-7 col-md-8 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Total Amount :</label>
                                </div>
                                <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="ttoalLiabilityAmount">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="row ">
                        <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-10 col-12 numOf-installments-section">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-7 col-md-8 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Number of Installments</label>
                                </div>
                                <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-7 col-12 top-margin-input">
                                    <input type="text" class="form-control top-margin-input-1" name="numOfInstallments">
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-10 col-12 amountPer-installment-section">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-7 col-md-8 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Amount per Installment</label>
                                </div>
                                <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="amountPerInstallment">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-12 liability-reason-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 emp-liability-lable-name">
                                    <span class="error">*</span>
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Reason</label>
                                </div>
                                <div class="col-xxl-5 col-lg-5 col-md-8 col-sm-7 col-12">
                                    <textarea class="form-control top-margin-input-1" name="empLiabilityReason" rows="5" cols="25"></textarea>
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


        <!-- Employee Acknowledge Section -->
        <div class="emp-acknowledgement-main-container">

            <div class="col-lg-12 emp-liability-top-info">
                <div class="col-xxl-8 col-lg-8 col-md-12 col-sm-12 col-12 emp-liability-lable-name">

                    <h4 class="emp-liability-heading">Employee Acknowledgement:</h4>
                </div>
                <br />
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <p>
                        I hereby acknowledge the above mentioned deduction from my salary. I accpet that I will not exit the country until I
                        repay the whole amount back to the company. If I breach this contract, I am solely responsible for any legal action
                        taken against me by the company.
                    </p>
                </div>
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

                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-sign-section">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-4 col-sm-5 col-8 emp-liability-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Sign</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empSign">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />

        <hr />
        <br />

        <!-- Signatures Div -->

        <!-- <div class="emp-acknowledgement-main-container">

            <div class="col-lg-12 emp-liability-top-info">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="col-xxl-8 col-lg-8 col-md-12 col-sm-12 col-12 emp-liability-lable-name">
                        <h4>Approvals:</h4>
                    </div>
                </div>
                <hr />

                <div class="col-lg-12 job-desc-top-info">
                    <div class="row">
                        <div class="col-lg-6 col-md-7 col-sm-10 col-12 depManager-sign-section">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 emp-liability-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Department Manager</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                                    <input type="text" class="form-control top-margin-input-1" name="depManager">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 emp-liability-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Sign</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="empSign">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row ">
                        <div class="col-lg-6 col-md-7 col-sm-10 col-12 finManager-signature-section">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 emp-liability-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Finance Manager</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                                    <input type="text" class="form-control top-margin-input-1" name="finManagerSign">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 reportingManager-signature-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Sign</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="repManagerSignDate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="row">

                        <div class="col-lg-6 col-md-7 col-sm-10 col-12 divHead-section">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 divHead-signature-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Divison Head</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="divHeadSign">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 divHead-signature-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Sign</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="divHeadSignDate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="row">

                        <div class="col-lg-6 col-md-7 col-sm-10 col-12 hrManager-signature-section">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-6 col-md-5 col-sm-5 col-12 hrManager-signature-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">HR Manager</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="hrManager">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 hrManager-signature-section-lable-name">
                                    <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Sign</label>
                                </div>
                                <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                    <input type="text" class="form-control top-margin-input-1" name="hrManagerSign">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> -->


    </form>

</div>
</br>
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