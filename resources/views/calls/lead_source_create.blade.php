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
    </style>
@section('content')
@can('Calls-modified')
<div class="card-header">
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        <h4 class="card-title">New Master Lead Source</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('lead_source.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <form action="{{ route('lead_source.store') }}" method="post" enctype="multipart/form-data"> 
    @csrf
                <div class="row"> 
					<div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Source Name : </label>
                        <input type="text" class="form-control" id="basicpill-firstname-input" name="source_name">
                    </div>
			        </div>  
                    </br>
                    </br> 
			        <div class="col-lg-12 col-md-12">
				    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
			        </div>  
                    </form>
		</br>
    </div>
    @endcan
@endsection
