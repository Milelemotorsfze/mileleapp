@extends('layouts.main')
<style>
 .form-container {
    background-color: #f7f7f7;
    border: 10px solid #ccc;
    padding: 20px;
}

.form-label {
    font-weight: bold;
}
textarea {
    width: 100%;
    resize: vertical;
}
.btn-g {
    margin-top: 20px;
}
    </style>
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-view');
                    @endphp
                    @if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">Prospecting</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('lead_source.index') }}" text-align: right>
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
    </a>
</div>
<div class="card-body">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
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
    <div class="row">
    <div class="col-lg-7 col-md-6 form-container">
    <form action="{{ route('strategy.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <label for="medium-input" class="form-label">Medium:</label>
                <select class="form-control" id="medium-input" name="medium">
                    <option value="Call">Call</option>
                    <option value="Email">Email</option>
                    <option value="SMS">SMS</option>
                    <option value="Meeting">Meeting</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="name-input" class="form-label">Time:</label>
                <input type="time" id="time-input" name="name" class="form-control" value="" required>
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="cost-input" class="form-label">Date:</label>
                <input type="date" id="cost-input" name="cost" class="form-control" value="" placeholder="Strategy Cost" required>
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="deal-value-input" class="form-label">Deal Value:</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">AED</span>
                    <input type="text" class="form-control" id="deal-value-input" aria-label="Deal Value">
                    <span class="input-group-text">.00</span>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-lg-3 col-md-6">
    <label for="follow-up-input" class="form-label">Follow Up:</label>
    <div>
        <label>
            <input type="radio" name="follow-up" id="auto-assign-option" value="not-required" checked> Not Required
        </label>
        <label>
            <input type="radio" name="follow-up" id="manual-assign-option" value="set-follow-up"> Set Follow Up
        </label>
    </div>
</div>
            <div class="col-lg-4 col-md-6">
                <label for="brand-model-input" class="form-label">Brand & Model Change:</label>
                <div>
                    <label>
                        <input type="radio" name="brand-model" id="auto-assign-option" value="not-required" checked> Not Required
                    </label>
                    <label>
                        <input type="radio" name="brand-model" id="manual-assign-option" value="modified-requirement"> Modified Requirement
                    </label>
                </div>
            </div>
        </div>
        
<div id="follow-up-div" style="display: none;">
        <hr>
        <h5>Follow Up</h5>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="medium-input" class="form-label">Medium:</label>
                <select class="form-control" id="medium-input" name="medium">
                    <option value="Call">Call</option>
                    <option value="Email">Email</option>
                    <option value="SMS">SMS</option>
                    <option value="Meeting">Meeting</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6">
                <label for="name-input" class="form-label">Time:</label>
                <input type="time" id="time-input" name="name" class="form-control" value="" required>
            </div>
            <div class="col-lg-4 col-md-6">
                <label for="cost-input" class="form-label">Date:</label>
                <input type="date" id="cost-input" name="cost" class="form-control" value="" placeholder="Strategy Cost" required>
            </div>
        </div>
</div>
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <label for="sales-notes-input" class="form-label">Sales Notes:</label>
                <textarea name="sales-notes" id="sales-notes-input" rows="5"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="submit" value="Submit" class="btn-g btn-success" />
            </div>
        </div>
    </form>
</div>
  <div class="col-lg-4 col-md-6" style="background-color: #f7f7f7; border: 10px solid #ccc; padding: 20px; margin-left: 30px;">
  <div class="col-lg-12 col-md-12">
    <h4 style="font-size: 20px; margin-bottom: 10px; text-align: center;">Customer Details</h4>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">Name:</label>
      <span style="font-size: 16px;">{{$dailyLead->name}}</span>
    </div>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">Phone:</label>
      <span style="font-size: 16px;">{{$dailyLead->phone}}</span>
    </div>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">Email:</label>
      <span style="font-size: 16px;">{{$dailyLead->email}}</span>
    </div>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">Alternative Phone:</label>
       <input type="number" id="time-input" name="name" class="form-control" value="">
    </div>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">Alternative Email:</label>
       <input type="text" id="time-input" name="name" class="form-control" value="">
    </div>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">Location:</label>
      <span style="font-size: 16px;">{{$dailyLead->location}}</span>
    </div>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">language:</label>
      <span style="font-size: 16px;">{{$dailyLead->language}}</span>
    </div>
    <hr>
    <div style="margin-bottom: 10px;">
      <label for="basicpill-firstname-input" style="font-weight: bold; margin-right: 5px;">Requirements:</label>
    </div>
<table class="table table-bordered" style="background-color: white;">
  <thead class="thead-light">
    <tr>
      <th scope="col">Brand</th>
      <th scope="col">Model</th>
    </tr>
  </thead>
  <tbody>
    @php
    $call_requirement = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $dailyLead->id)
        ->get();
        foreach ($call_requirement as $call_requirement) 
        {
    @endphp
    <tr>
      <td>{{ $call_requirement->brand_name}}</td>
      <td>{{ $call_requirement->model_line}}</td>
      </tr>
    @php
    }
    @endphp
  </tbody>
</table>
  </div>
</div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('#time-input').timepicker({
            timeFormat: 'HH:mm',
            interval: 15,
            minTime: '00:00',
            maxTime: '23:45',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
    });
    $(document).ready(function() {
    // When the radio button selection changes
    $('input[name="follow-up"]').change(function() {
        var selectedValue = $(this).val();
        
        if (selectedValue === 'set-follow-up') {
            // Show the follow-up div
            $('#follow-up-div').show();
        } else {
            // Hide the follow-up div
            $('#follow-up-div').hide();
        }
    });
});
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection