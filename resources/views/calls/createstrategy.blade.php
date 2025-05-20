@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
@if ($hasPermission)
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
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('lead_source.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
            <div class="row">
			</div>  
			<form action="{{route('strategy.store')}}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="row"> 
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Name : </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder = "Strategy Name" required>
                    </div>
                <div class="col-lg-2 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Cost : </label>
                        <input type="Number" name="cost" class="form-control" value="{{ old('cost') }}" min="0" pattern="\d+" placeholder = "Strategy Cost" required>
                    </div>
                    <div class="col-lg-2 col-md-6">
    <label for="basicpill-firstname-input" class="form-label">Currency:</label>
    <select class="form-control" data-trigger name="currency">
        <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>AED</option>
        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
        <option value="EURO" {{ old('currency') == 'EURO' ? 'selected' : '' }}>EURO</option>
        <option value="SAR" {{ old('currency') == 'SAR' ? 'selected' : '' }}>SAR</option>
    </select>
</div>
                    <div class="col-lg-2 col-md-6">
    <label for="basicpill-firstname-input" class="form-label">Start Date:</label>
    <input type="date" name="start_date" class="form-control" value="" placeholder="Strategy Cost">
    <input type="hidden" name="lead_source_id" class="form-control" value="{{$id}}" placeholder="Strategy Cost">
</div>
<div class="col-lg-2 col-md-6">
    <label for="basicpill-firstname-input" class="form-label">End Date:</label>
    <input type="date" name="end_date" class="form-control" value="" id="end-date-input">
    <input type="checkbox" name="one_day_activity" id="one-day-activity-checkbox" value="auto-assign"> One Day Activity
</div>
			        <div class="col-lg-1 col-md-12">
                 <input type="submit" name="submit" value="Submit" class="btn btn-success" style="margin-top: 40px;" />
                    </div>
                    </div> 
			        </div>  
                    </form>
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>Name</th>
                    <th>Cost</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($strategies as $strategy)
				 @foreach ($strategiesDates->where('strategies_id', $strategy->id) as $date)
                    <tr data-id="1">
                    @php
            $startDate = \Carbon\Carbon::parse($date->starting_date);
            $endDate = \Carbon\Carbon::parse($date->ending_date);
            $numberOfDays = $endDate->diffInDays($startDate);
            @endphp
            @if ($numberOfDays == 0)
            @php
             $numberOfDays = "Same Day";
           @endphp
            @endif
                        <td>{{ $strategy->name }}</td>
                        <td>{{ $date->cost }}</td>
                        <td>{{ $startDate->format('d-m-Y') }}</td>
                        <td>{{ $endDate->format('d-m-Y') }}</td>
                        <td>{{ $numberOfDays }}</td>
                        @php
    date_default_timezone_set('Asia/Dubai');
    $currentDate = date('Y-m-d'); // Get the current date without time
@endphp

@if(strtotime($endDate) > strtotime($currentDate))
    <td><label class="badge badge-soft-success">Active</label></td>
@elseif(strtotime($endDate) == strtotime($currentDate))
    <td><label class="badge badge-soft-Info">Active</label></td>
@else
    <td><label class="badge badge-soft-danger">In Active</label></td>
@endif
                        <td><a title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('strategy.show',$strategy->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a title="Delete"
   data-placement="top"
   class="btn btn-sm btn-danger"
   onclick="deleteRow({{ $strategy->id }})">
  <i class="fa fa-times" aria-hidden="true"></i>
</a></td>
                    </tr>
		          @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
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
function deleteRow(strategyId) {
  // Show a confirmation dialog
  if (confirm('Are you sure you want to delete this item?')) {
    $.ajax({
      url: '/strategy/' + strategyId,
      type: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        // Reload the page after successful deletion
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
}
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
