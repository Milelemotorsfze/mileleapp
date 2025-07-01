@extends('layouts.main')
<style>
    .select2-container {
        width: 100% !important;
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
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">Bulk Calls & Messages</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('calls.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    @if ($errors->has('source_name'))
    <div id="error-message" class="alert alert-danger">
        {{ $errors->first('source_name') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('error'))
    <div id="error-message" class="alert alert-danger">
        {!! session('error')['message'] !!}
        <br>
        @if(isset(session('error')['fileLink']))
        <a href="{{ session('error')['fileLink'] }}" class="btn btn-primary">Download Rejected Records</a>
        @endif
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {!! session('success')['message'] !!}
        <br>
        <a href="{{ session('success')['fileLink'] }}" class="btn btn-primary">Click here</a> to download the rejected records.
    </div>
    @endif
    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('calls.bulkLeadsExcelDataUplaod') }}" text-align: right><i class="fa fa-download" aria-hidden="true"></i> Download Sample File</a>
    <br>
    <div class="row">
    </div>
    <form action="{{route('calls.uploadingbulk')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="basicpill-firstname-input" class="form-label">Upload File : </label>
                <input type="file" name="file" class="form-control">
            </div>
        </div>
</div>
</br>
</br>
<div class="col-lg-12 col-md-12">
    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" required />
</div>
</form>
</br>
</div>
@else
@php
redirect()->route('home')->send();
@endphp
@endif
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#language').select2();
        $('#source').select2();
        $('#type').select2();

        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 10000);

        $("form").submit(function(event) {
            var fileInput = $("input[name='file']");
            if (!fileInput[0].files.length) {
                event.preventDefault();
                alert("Please upload a valid Excel file before submitting.");
            }
        });
    });
</script>

@endpush