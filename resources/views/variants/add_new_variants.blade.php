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
  border-radius: 5px;
}
@media (max-width: 768px) {
  .btn.btn-success.btncenter {
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 3px;
  }
}
@media (max-width: 576px) {
  .btn.btn-success.btncenter {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 2px;
  }
}
    </style>
@section('content')
@if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4')
@can('Calls-modified')
@if ($errors->has('source_name'))
            <div id="error-message" class="alert alert-danger">
                {{ $errors->first('source_name') }}
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
        <h4 class="card-title">New Variants With Colours</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('lead_source.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div> 
                    <form action="{{ route('calls.storenewvarinats') }}" method="post" enctype="multipart/form-data"> 
                    @csrf
                    <div class="row"> 
					<div class="col-lg-4 col-md-6">
                    <span class="error">*</span>
                    <label for="basicpill-firstname-input" class="form-label">Variant Name : </label>
                    <input type="text" class="form-control" id="basicpill-firstname-input" name="name">
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">*</span>
                    <label for="basicpill-firstname-input" class="form-label">Interior Colour:</label>
                    <input type="text" placeholder="Interior Colour" name="int_colour" list="interiorColors" class="form-control" id="locationInput">
                    <datalist id="interiorColors">
                    @foreach ($interiorColors as $interiorColors)
                    <option value="{{ $interiorColors }}" data-value="{{ $interiorColors }}">{{ $interiorColors }}</option>
                    @endforeach
                    </datalist>
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">*</span>
                    <label for="basicpill-firstname-input" class="form-label">Exterior Colour:</label>
                    <input type="text" placeholder="Exterior Colour" name="ext_colour" list="exteriorColors" class="form-control" id="locationInput">
                    <datalist id="exteriorColors">
                    @foreach ($exteriorColors as $exteriorColors)
                    <option value="{{ $exteriorColors }}" data-value="{{ $exteriorColors }}">{{ $exteriorColors }}</option>
                    @endforeach
                    </datalist>
                    </div>
                    </div>  
                    </br>
                    </br> 
			        <div class="col-lg-12 col-md-12">
				    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
			        </div>  
                    </form>
                    <br>
    </div>
    <script>
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
@endcan
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
