@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .select2-container {
        width: 100% !important;
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

    .job-description-lable-name,
    .job-description-lable-name-1 {
        /* border: 0.1px solid #ced4da; */
        color: white;
        background-color: #042849;
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .job-description-lable-name-1 {
        margin-top: 20px;

    }

    .job-desc-top-info {
        margin-left: 10px;
    }

    input.job-desc-signature {
        padding: 60px 0;
    }

    .job-desc-signature-name {
        display: flex;
        text-align: center;
        justify-items: center;
    }

    .form-control {
        border-radius: 0 !important;
    }

    .top-margin-input {
        margin-top: 1px !important;
    }

    .top-margin-input-1 {
        padding: 11px 0px !important;
    }

    div.col-xxl-10.col-lg-10.col-md-9 {
        padding: 0px 20px 0px 0px;
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

    <form action="" method="post" enctype="multipart/form-data">
        <br />

        <div class="row">

            <div class="col-lg-12 job-desc-top-info">
                <div class="row">
                    <div class="col-xxl-2 col-lg-2 col-md-3 col-sm-3 col-4 job-description-lable-name">

                        <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Job Title</label>
                    </div>
                    <div class="col-xxl-10 col-lg-10 col-md-9 col-sm-9 col-8 top-margin-input">
                        <input type="text" class="form-control top-margin-input-1" name="jobtitle">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-2 col-lg-2 col-md-3 col-sm-3 col-4 job-description-lable-name">

                        <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Department</label>
                    </div>
                    <div class="col-xxl-10 col-lg-10 col-md-9 col-sm-9 col-8">
                        <input type="text" class="form-control top-margin-input-1" name="department">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-2 col-lg-2 col-md-3 col-sm-3 col-4 job-description-lable-name">

                        <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Location</label>
                    </div>
                    <div class="col-xxl-10 col-lg-10 col-md-9 col-sm-9 col-8">
                        <input type="text" class="form-control top-margin-input-1" name="location">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-2 col-lg-2 col-md-3 col-sm-3 col-4 job-description-lable-name">

                        <label for="basicpill-firstname-input" class="col-form-label widthinput heading-name">Reporting To</label>
                    </div>
                    <div class="col-xxl-10 col-lg-10 col-md-9 col-sm-9 col-8">
                        <input type="text" class="form-control top-margin-input-1" name="reportingto">
                    </div>
                </div>
                </br>
            </div>
        </div>


        <div class="row">

            <div>
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="basicpill-firstname-input" class="form-label heading-name">Job Purpose</label>
                </div>
                <div class="col-lg-12  ">

                    <textarea cols="25" rows="3" class="form-control" name="jobpurpose" placeholder="Job Purpose"></textarea>
                </div>
            </div>

            <div>
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="basicpill-firstname-input" class="form-label heading-name">Duties and Responsibilities (Generic) of the position </label>
                </div>
                <div class="col-lg-12  ">

                    <textarea cols="25" rows="7" class="form-control" name="positionduties" placeholder="Deneric Duties and Responsibilities of the position"></textarea>
                </div>
            </div>


            <div>
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="basicpill-firstname-input" class="form-label heading-name">Skills required to fulfil the position </label>
                </div>
                <div class="col-lg-12  ">

                    <textarea cols="25" rows="7" class="form-control" name="requiredskills" placeholder="Required Skills"></textarea>
                </div>
            </div>


            <div>
                <div class="col-lg-12  job-description-lable-name-1">
                    <label for="basicpill-firstname-input" class="form-label heading-name">Position Qualification (Academic & Professional) </label>
                </div>
                <div class="col-lg-12  ">

                    <textarea cols="25" rows="7" class="form-control" name="positionqualification" placeholder="Position Qualification"></textarea>
                </div>
            </div>

            <div>
                <div class="col-lg-12  job-description-lable-name-1">
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
            </div>

        </div>
</div>

</div>
</br>
</br>
<div class="col-lg-12 col-md-12 col-sm-12 col-12">
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

</script>


@endpush