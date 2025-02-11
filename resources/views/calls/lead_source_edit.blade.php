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
  /* font-size: 17px;
  border-radius: 10px; */
}

    </style>
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
  @if ($hasPermission)
<div class="card-header">
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        <h4 class="card-title">Edit Master Lead Source</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('lead_source.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div> 
    <form method="POST" action="{{ route('lead_source.update', $record->id) }}">
    @csrf
    @method('PUT')
                <div class="row"> 
					<div class="col-lg-3 col-md-4 col-sm-8 col-12">
          <span class="error">*</span>
                        <label for="basicpill-firstname-input" class="form-label">Source Name : </label>
                        <input type="text" name="source_name" class="form-control" value="{{ $record->source_name }}" required>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-8 col-12">
                    <span class="error">*</span>
                        <label for="basicpill-firstname-input" class="form-label">Status : </label>
                        <select name="status" class="form-control">
                        <option value="active" {{ $record->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $record->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
                    </div>
			        </div>  
                    </br>
                    </br> 
			        <div class="col-lg-12 col-md-12 text-center">
				    <input type="submit" name="submit" value="Submit" class="btn btn-success" />
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
