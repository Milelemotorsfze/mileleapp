@extends('layouts.main')
@section('content')

@php
$bg_Img = url('images/Salary-Certificates/Sal_Cer_Bg_Images/milele_motors_fze_header.jpg');
@endphp

<style>
    @page {
        size: A4;
    }

    @media only screen and (min-device-width: 1200px) {
        .container {
            max-width: 750px;
        }
    }

    table {
        font-family: arial, sans-serif;
        width: 100%;
    }

    .border-outline {
        border: 1px solid #0f0f0f;
        padding: 10px !important;
    }
</style>

<div class="row">
    <div class="container mb-4">
        <form id="downloadEmployeeSalaryCertificate" name="downloadEmployeeSalaryCertificate" action="{{ route('employeeRelation.salaryCertificate.generateSalaryCertificate', $certificate->id) }}" class="mb-3">
            @csrf
            <input type="hidden" name="width" id="width" value="">
            <input type="hidden" name="download" value="1">
            <div class="text-end mt-3">
                <a class="btn btn-info float-end" style="margin-left: 10px;" href="{{ url()->previous() }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                </a>
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-download']);
                @endphp
                @if ($hasPermission)
                <button type="submit" class="btn btn-primary mr-3">Download <i class="fa fa-download"></i></button>
                @endif
            </div>
        </form>

        <div class="border-outline">
            @include('employee_relation.salary_certificate.download_template')
        </div>


    </div>
</div>

@endsection