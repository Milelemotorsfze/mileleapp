@extends('layouts.main')
@section('content')
@can('daily-leads-create')
<div class="card-header">
        <h4 class="card-title">Add New Quotation</h4>
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
        <form method="POST" action="" enctype="multipart/form-data">
        @csrf
            <div class="row">
			</div>  
                <div class="row"> 
					<div class="col-lg-2 col-md-2">
                        <label for="basicpill-firstname-input" class="form-label">Customer Name </label>
                        <input type ="text" class="form-control" name="" placeholder = "" value = "{{ $data->name }}" readonly>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Company</label>
                        <input type ="text" class="form-control" name="" placeholder = "" value = "">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Contact No : </label>
                        <input type ="text" class="form-control" name="" placeholder = "" value = "{{ $data->phone }}" readonly>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Email : </label>
                        <input type ="text" class="form-control" name="" placeholder = "" value = "{{ $data->email }}" readonly>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Address : </label>
                    <input type ="text" class="form-control" name="" placeholder = "" value = "">
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Source : </label>
                        {{ Form::select('source', [
                        'Website' => 'Website',
                        'Facebook' => 'Facebook',
						'Dubizzle' => 'Dubizzle',
                        'Direct Call' => 'Direct Call',
                        'Email' => 'Email',
                        'Linkdin' => 'Linkdin',
                        'Classified ADs' => 'Classified ADs',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Language : </label>
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
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-xs-6 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Location:</strong>
                            <select name="location" id="country" class="form-control mb-1">
                                <option value="">Select Location</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
                        <textarea name="remarks" id="editor"></textarea>
                    </div>
			        </div>  
                    </br>
                    </br> 
			        <div class="col-lg-12 col-md-12">
				    <input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
			        </div>  
		{!! Form::close() !!}
		</br>
    </div>
    @endcan
@endsection
