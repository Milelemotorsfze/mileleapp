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
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
@can('Calls-modified')
<div class="card-header">
        <h4 class="card-title">Lead Soruce Strategies</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('calls.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
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
			</div>  
			<form action="{{route('calls.uploadingbulk')}}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="row"> 
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Name : </label>
                        <input type="text" name="phone" class="form-control" value="" placeholder = "Strategy Name">
                    </div>
                <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Cost : </label>
                        <input type="Number" name="phone" class="form-control" value="" placeholder = "Strategy Cost">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Start Date : </label>
                        <input type="date" name="phone" class="form-control" value="" placeholder = "Strategy Cost">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">End Date : </label>
                        <input type="date" name="phone" class="form-control" value="" placeholder = "Strategy Cost"><br>
                        <input type="checkbox" name="sales-option" id="auto-assign-option" value="auto-assign" > One Day Activity
                    </div>
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
@push('scripts')
    <script type="text/javascript">
</script>
@endpush