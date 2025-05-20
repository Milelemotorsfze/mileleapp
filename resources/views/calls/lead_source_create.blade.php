@extends('layouts.main')
<style>

.error {
  color: red;
}
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
  background-color: #22973c;
  /* font-size: 17px; */
  /* border-radius: 5px; */
}

/* Responsive Styles */
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
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
  @if ($hasPermission)
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
        <h4 class="card-title">New Master Lead Source</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('lead_source.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div> 
    <form action="{{ route('lead_source.store') }}" method="post" enctype="multipart/form-data"> 
    @csrf
                <div class="row"> 
					<div class="col-lg-3 col-md-4 col-sm-8 col-12">
          <span class="error">*</span>
                        <label for="basicpill-firstname-input" class="form-label">Source Name : </label>
                        <input type="text" class="form-control" id="basicpill-firstname-input" name="source_name" required>
                    </div>
			        </div>  
                    </br>
                    </br> 
			        <div class="col-lg-12 col-md-12 text-center">
				    <input type="submit" name="submit" value="Submit" class="btn btn-success" />
			        </div>  
                    </form>
                    <br>
       
    </div>
    <script>
        // Set timer for error message
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);

        // Set timer for success message
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
