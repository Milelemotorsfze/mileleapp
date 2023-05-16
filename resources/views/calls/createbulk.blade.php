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
        <h4 class="card-title">Bulk Calls & Messages</h4>
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
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Source : </label>
                        <select name="source" id="source" class="form-control mb-1">
                                @foreach ($LeadSource as $LeadSource)
                                    <option value="{{ $LeadSource->id }}">{{ $LeadSource->source_name }}</option>
                                @endforeach
                                </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Preferred Language : </label>
                        {{ Form::select('language', [
                        'English' => 'English',
                        'Arabic' => 'Arabic',
                        'Russian' => 'Russian',
                        'Urdu' => 'Urdu',
                        'Hindi' => 'Hindi',
						'Kannada' => 'Kannada',
                        'French' => 'French',
                        'Malayalam' => 'Malayalam',
                        'Tamil' => 'Tamil',
                        'spanish' => 'Spanish',
                        'portuguese' => 'Portuguese',
                        'shona' => 'Shona',
                        ], null, ['class' => 'form-control', 'id' => 'language']) }}
                    </div>
                <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Type : </label>
                        {{ Form::select('type', [
                        'Not Mentioned' => 'Not Mentioned',
                        'Export' => 'Export',
                        'Local' => 'Local',
                        'Other' => 'Other',
                        ], null, ['class' => 'form-control', 'id' => 'type']) }}
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Upload File : </label>
                        <input type="file" name="file" class="form-control" >
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
$('#language').select2();
$('#source').select2();
$('#type').select2();
</script>
@endpush