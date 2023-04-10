@extends('layouts.main')
@section('content')
@can('Calls-modified')
<div class="card-header">
        <h4 class="card-title">Add New Sales Persons Language</h4>
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
        {!! Form::open(array('route' => 'sales_person_languages.store','method'=>'POST')) !!}
            <div class="row">
			</div>  
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row"> 
					<div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
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
