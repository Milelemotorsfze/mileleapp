extends('layouts.main')
<style>
    .error {
        color: #FF0000;
    }

    input:focus {
        border-color: #495057 !important;
    }

    select:focus {
        border-color: #495057 !important;
    }

    .paragraph-class {
        color: red;
        font-size: 11px;
    }

    .overlay {
        position: fixed;
        /* Positioning and size */
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(128, 128, 128, 0.5);
        /* color */
        display: none;
        /* making it hidden by default */
    }

    .drop-class {
        padding-top: 10px;
    }

    .widthinput {
        height: 32px !important;
    }
</style>
@section('content')
<!-- @can('warranty-create') -->
<!-- @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create']);
@endphp -->
<!-- @if ($hasPermission) -->
<div class="card-header">
    <h4 class="card-title">Create Achievement Certification Request</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <form id="createWarrantyForm" name="createWarrantyForm" method="POST" enctype="multipart/form-data" action="{{ route('employeeRelation.achievementCertificate.store') }}">
        @csrf
        <div class="form-group">
            <label for="achievement_name">Achievement Name</label>
            <input type="text" name="achievement_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="purpose_of_request">Purpose of Request</label>
            <input type="text" name="purpose_of_request" class="form-control" required>
        </div>
        <br>

        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-sm" id="submit" style="float:right;">Submit</button>
        </div>
    </form>
</div>
<input type="hidden" id="indexValue" value="">
<div class="overlay"></div>
<!-- @endif -->
<!-- @endcan -->