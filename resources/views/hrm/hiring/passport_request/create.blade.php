@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .select2-container {
        width: 100% !important;
    }

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
    <form action="" method="post" enctype="multipart/form-data">
        <br />

        <div class="row">

            <div class="col-lg-12 job-desc-top-info">
                <div class="row">
                    <!-- Employee name Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-name-section-div">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-4 col-sm-5 col-8 passport-request-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Employee Name</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                                <input type="text" class="form-control top-margin-input-1" name="empName">
                            </div>
                        </div>
                    </div>

                    <!-- Employee ID Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-id-section-div">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-4 col-sm-5 col-8 passport-request-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Employee ID</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="empId">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row ">
                    <!-- Designation Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 emp-designation-section-div">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-4 col-sm-5 col-12 passport-request-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Designation</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                                <input type="text" class="form-control top-margin-input-1" name="empDesignation">
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Number Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 mobile-num-section-div">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-4 col-sm-5 col-12 passport-request-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Mobile No.</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="mobileNum">
                            </div>
                        </div>
                    </div>
                </div>
                <br />

                <div class="row">

                    <!-- Department Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 dep-section-div">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-4 col-sm-5 col-12 passport-request-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Department:</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                                <input type="text" class="form-control top-margin-input-1" name="department">
                            </div>
                        </div>
                    </div>

                    <!-- Location Section -->
                    <div class="col-lg-6 col-md-6 col-sm-10 col-12 location-section-div">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-5 col-md-4 col-sm-5 col-12 passport-request-lable-name">
                                <span class="error">*</span>
                                <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Location</label>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12 top-margin-input">
                                <input type="text" class="form-control top-margin-input-1" name="location">
                            </div>
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
                            <label for="basicpill-firstname-input" class="form-label"><strong>I, the undersigned,</strong> consent to authorize
                                the Human Resource Department to take possession of my passport for purposes of :</label>
                            <div class="row">
                                <!-- First Row of List Items -->
                                <div class="col-lg-4">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <input type="checkbox" id="item1" name="item1">
                                            <label for="item1">Safekeeping</label>
                                        </li>
                                        <li class="list-group-item">
                                            <input type="checkbox" id="item2" name="item2">
                                            <label for="item2">My dealing with Cash</label>
                                        </li>
                                        <li class="list-group-item">
                                            <input type="checkbox" id="item3" name="item3">
                                            <label for="item3">My dealing with Sensitive Data</label>
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
                            <label for="basicpill-firstname-input" class="form-label">
                                <strong>I, the undersigned,</strong> would like to collect my passport for the following purpose :
                            </label>
                            <div class="row">
                                <?php foreach ($listItems as $item) : ?>
                                    <div class="col-lg-4 col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <input type="checkbox" id="<?= strtolower(str_replace(' ', '', $item)); ?>" name="<?= strtolower(str_replace(' ', '', $item)); ?>">
                                                <label for="<?= strtolower(str_replace(' ', '', $item)); ?>"><?= $item; ?></label>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Other Checkbox and Input -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="other-checklist-div">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check">
                                                    <input type="checkbox" id="item10" name="item10" class="form-check-input">
                                                    <label for="item10" class="form-check-label">Other, please specify:</label>
                                                </div>
                                                <div class="other-input-container">
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-12">
                                                        <input type="text" class="form-control" name="otherReason" id="otherReason">
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
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="amountpercentageDropDownInputContainer">
                <h5>Signatures</h5>
            </div>
        </div>
        <hr />

        <div class="col-lg-12 job-desc-top-info">
            <div class="row">
                <!-- Employee Signature Section -->
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

                <!-- Employee Sign Date Section -->
                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 passport-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="text" class="form-control top-margin-input-1" name="empSign">
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row ">
                <!-- Reporting Manager Signature Section -->
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

                <!-- Reporting Manager Signature Date Section -->
                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 reportingManager-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="text" class="form-control top-margin-input-1" name="repManagerSignDate">
                        </div>
                    </div>
                </div>
            </div>
            <br />

            <div class="row">

                <!-- Division Head Signature Section -->
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

                <!-- Division Head Signature Date Section -->
                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 divHead-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="text" class="form-control top-margin-input-1" name="divHeadSignDate">
                        </div>
                    </div>
                </div>
            </div>
            <br />

            <div class="row">

                <!-- HR Manager Signature Section -->
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

                <!-- HR Manager Signature Date Section -->
                <div class="col-lg-6 col-md-5 col-sm-10 col-12 sign-date-section-div">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-4 col-md-4 col-sm-5 col-12 hrManager-signature-section-lable-name">
                            <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Date</label>
                        </div>
                        <div class="col-xxl-5 col-lg-6 col-md-7 col-sm-7 col-12">
                            <input type="text" class="form-control top-margin-input-1" name="hrManagerSign">
                        </div>
                    </div>
                </div>
            </div>

        </div>

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
    function showPassportRequestInput(element) {
        var selectedValue = element.value;
        document.getElementById('submitPassportInputContainer').style.display = selectedValue == '1' ? 'block' : 'none';
        document.getElementById('releasePassportInputContainer').style.display = selectedValue == '2' ? 'block' : 'none';
    }
</script>
@endpush