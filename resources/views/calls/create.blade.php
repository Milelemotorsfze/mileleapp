@extends('layouts.main')
@section('content')
@can('Calls-modified')
<div class="card-header">
        <h4 class="card-title">New Calls & Messages</h4>
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
        {!! Form::open(array('route' => 'calls.store','method'=>'POST')) !!}
            <div class="row">
			</div>  
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row"> 
					<div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Phone : </label>
                        {!! Form::number('phone', null, array('placeholder' => 'Phone','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Email : </label>
                        {!! Form::email('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                        <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}">
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Client Demand : </label>
                        {!! Form::text('demand', null, array('placeholder' => 'Demand','class' => 'form-control')) !!}
                        <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}">
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
