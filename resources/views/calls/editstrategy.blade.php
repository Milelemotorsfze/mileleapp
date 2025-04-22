@extends('layouts.table')
<style>
.select2-container {
  width: 100% !important;
}
.form-label[for="basicpill-firstname-input"] {
  margin-top: 12px;
}
.error 
    {
        color: #FF0000;
    }
    .iti 
    { 
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
    </style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@section('content')
@if ($errors->has('start_date') || $errors->has('end_date'))
    <div id="error-message" class="alert alert-danger">
        Please Enter the Correct Dates.
    </div>
@endif
        @if (session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
<div class="card-header">
        <h4 class="card-title">Lead Soruce Strategies</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('strategy.edit',$strategies[0]->lead_source_id) }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
            <div class="row">
			</div>
            <form action="{{ route('strategy.updaters', $strategies[0]->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row"> 
        <div class="col-lg-3 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $strategies[0]->name) }}" placeholder="Strategy Name" required>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Cost:</label>
            @php
                $cost = explode(' ', $strategiesDates[0]->cost);
                $costValue = $cost[0] ?? '';
                $currencyValue = $cost[1] ?? '';
            @endphp
            <input type="number" name="cost" class="form-control" value="{{ old('cost', $costValue) }}" min="0" pattern="\d+" placeholder="Strategy Cost" required>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Currency:</label>
            <select class="form-control" data-trigger name="currency">
                <option value="AED" {{ old('currency', $currencyValue) === 'AED' ? 'selected' : '' }}>AED</option>
                <option value="USD" {{ old('currency', $currencyValue) === 'USD' ? 'selected' : '' }}>USD</option>
                <option value="EURO" {{ old('currency', $currencyValue) === 'EURO' ? 'selected' : '' }}>EURO</option>
                <option value="SAR" {{ old('currency', $currencyValue) === 'SAR' ? 'selected' : '' }}>SAR</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Start Date:</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $strategiesDates[0]->starting_date) }}" placeholder="Strategy Cost">
            <input type="hidden" name="lead_source_id" class="form-control" value="{{ $id }}" placeholder="Strategy Cost">
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">End Date:</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $strategiesDates[0]->ending_date) }}" id="end-date-input">
            <input type="checkbox" name="one_day_activity" id="one-day-activity-checkbox" value="auto-assign" {{ old('one_day_activity') ? 'checked' : '' }}> One Day Activity
        </div>
        <div class="col-lg-1 col-md-12">
            <input type="submit" name="submit" value="submit" class="btn btn-success" style="margin-top: 40px;" />
        </div>
    </div>
</form>
    </div>
    <script>
    $(document).ready(function() {
    $('#one-day-activity-checkbox').change(function() {
        var startDateValue = $('input[name="start_date"]').val();
        
        if (startDateValue) {
            if ($(this).is(':checked')) {
                $('#end-date-input').prop('disabled', true).val(startDateValue);
            } else {
                $('#end-date-input').prop('disabled', false).val('');
            }
        } else {
            alert('Please enter a valid start date.');
            $(this).prop('checked', false);
        }
    });

    $('input[name="start_date"]').change(function() {
        if ($('#one-day-activity-checkbox').is(':checked')) {
            var startDateValue = $(this).val();
            $('#end-date-input').val(startDateValue);
        }
    });
});
        // Set timer for error message
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);

        // Set timer for success message
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
        document.addEventListener('DOMContentLoaded', function() {
  var startingDateInput = document.querySelector('input[name="start_date"]');
  var endingDateInput = document.querySelector('input[name="end_date"]');

  endingDateInput.addEventListener('change', function() {
    var startingDateValue = startingDateInput.value;
    var endingDateValue = endingDateInput.value;
    
    if (startingDateValue && endingDateValue) {
      var startingDate = new Date(startingDateValue);
      var endingDate = new Date(endingDateValue);
      
      if (endingDate <= startingDate) {
        alert('The ending date must be later than the starting date.');
        endingDateInput.value = '';
      }
    }
  });
});
</script>
@endsection
