@extends('layouts.main')
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
  @if ($hasPermission)
<div class="card-header">
        <h4 class="card-title">Exports Leads</h4>
    </div>
    <div class="card-body">
    <form action="{{ route('calls.exportsleadsform') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
    <div class="col-lg-2 col-md-6">
            <label for="source" class="form-label">Source:</label>
            <select name="source" id="source" class="form-control">
            <option value="">Select the Source</option>
            @foreach ($LeadSource as $LeadSource)
                <option value="{{ $LeadSource->id }}">{{ $LeadSource->source_name }}</option>
            @endforeach
            </select>
        </div>
    <div class="col-lg-2 col-md-6">
            <label for="strategy" class="form-label">Strategies:</label>
            <select name="strategy" id="strategy" class="form-control">
            <option value="">Select the Strategy</option>
            @foreach ($strategies as $strategy)
                <option value="{{ $strategy->id }}">{{ $strategy->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="salesperson" class="form-label">Sales Person:</label>
            <select name="salesperson" id="salesperson" class="form-control">
            <option value="">Select the Sales Person</option>
            @foreach ($sales_persons as $sales_person)
        @php
            $sales_person_details = DB::table('users')->where('id', $sales_person->model_id)->first();
            $sales_person_name = $sales_person_details->name;
        @endphp
        <option value="{{ $sales_person_name }}" data-id="{{ $sales_person->model_id }}">{{ $sales_person_name }}</option>      
    @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="modelline" class="form-label">Model Line:</label>
            <select name="modelline" id="modelline" class="form-control">
            <option value="">Select the Model Line</option>
            @foreach ($modelLineMasters as $modelLineMaster)
        @php
            $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
            $brand_name = $brand ? $brand->brand_name : 'Unknown Brand';
        @endphp 
        <option value="{{ $modelLineMaster->model_line }}" data-value="{{ $modelLineMaster->id }}">{{ $brand_name }} / {{ $modelLineMaster->model_line }}</option>
    @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="priority" class="form-label">Priority:</label>
            <select name="priority" id="priority" class="form-control">
            <option value="">Select the Priority</option>
            <option value="High" data-value="High">High</option>
            <option value="Normal" data-value="Normal">Normal</option>
            <option value="Low" data-value="Low">Low</option>
            </select>
        </div>
</div>
</br>
<div class="row">
        <div class="col-lg-2 col-md-6">
            <label for="location" class="form-label">Location:</label>
            <select name="location" id="location" class="form-control">
            <option value="">Select the Location</option>
            @foreach ($countries as $country)
                    <option value="{{ $country }}" data-value="{{ $country }}">{{ $country }}</option>
                    @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="strategy" class="form-label">Language:</label>
            <select name="language" id="language" class="form-control">
            <option value="">Select the Language</option>
            <option value="English" data-value="English">English</option>
                        <option value="Arabic" data-value="English">Arabic</option>
                        <option value="Russian" data-value="English">Russian</option>
                        <option value="Urdu" data-value="English">Urdu</option>
                        <option value="Hindi" data-value="English">Hindi</option>
                        <option value="Kannada" data-value="English">Kannada</option>
                        <option value="French" data-value="English">French</option>
                        <option value="Malayalam" data-value="English">Malayalam</option>
                        <option value="Tamil" data-value="English">Tamil</option>
                        <option value="Spanish" data-value="English">Spanish</option>
                        <option value="Portuguese" data-value="English">Portuguese</option>
                        <option value="Shona" data-value="English">Shona</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="fromDate" class="form-label">From:</label>
            <input type="date" id="fromDate" name="fromDate" class="form-control">
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="toDate" class="form-label">To:</label>
            <input type="date" id="toDate" name="toDate" class="form-control">
        </div>
        </div>
</br>
        <div class="col-lg-12 col-md-12">
				    <input type="submit" name="submit" value="Export" class="btn btn-success btncenter" />
			        </div>
</form>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@push('scripts')
<script>
$(document).ready(function () {
  $('#strategy').select2();
  $('#salesperson').select2();
  $('#modelline').select2();
  $('#priority').select2();
  $('#location').select2();
  $('#language').select2();
  $('#source').select2();

  let today = new Date().toISOString().split('T')[0];
    $('#fromDate, #toDate').attr('max', today);

    $('form').on('submit', function (e) {
        const from = $('#fromDate').val();
        const to = $('#toDate').val();

        if (!from || !to) {
            e.preventDefault();
            alert('Both From and To dates are required.');
            return;
        }

        if (from > to) {
            e.preventDefault();
            alert('From date must be less than or equal to To date.');
            return;
        }
    });
});
</script>
@endpush