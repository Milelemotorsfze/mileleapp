@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create New Bill of Lading Form</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('blfrom.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row"> 
					<div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Number</label>
                        {!! Form::text('text', null, array('placeholder' => 'Enter BL Number','class' => 'form-control')) !!}
                    </div>
					<div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">SO Number</label>
                        {!! Form::text('text', null, array('placeholder' => 'Enter SO Number','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">No of Containers</label>
                        {{ Form::select('No of Containers', [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Trackable on Web</label>
                        {{ Form::select('No of Containers', [
                        'Yes' => 'Yes',
                        'No' => 'No',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Looks Genuine</label>
                        {{ Form::select('No of Containers', [
                        'Yes' => 'Yes',
                        'No' => 'No',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Is the Shipper Dealer</label>
                        {{ Form::select('No of Containers', [
                        'Yes' => 'Yes',
                        'No' => 'No',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">VIN Number</label>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">SO Destination Country</label>
                        {{ Form::select('No of Containers', [
                        'Yes' => 'Yes',
                        'No' => 'No',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Vehicle Exit Country</label>
                        {{ Form::select('No of Containers', [
                        'Yes' => 'Yes',
                        'No' => 'No',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Destination Country</label>
                        {{ Form::select('No of Containers', [
                        'Yes' => 'Yes',
                        'No' => 'No',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Vehicle Exit Country</label>
                        {{ Form::select('No of Containers', [
                        'Yes' => 'Yes',
                        'No' => 'No',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Port</label>
                        {!! Form::text('text', null, array('placeholder' => 'Enter Port','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Date</label>
                        {!! Form::date('date', null, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Real - NonReal</label>
                        {{ Form::select('No of Containers', [
                        'Real' => 'Real',
                        'Non-Real' => 'Non-Real',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Status</label>
                        {{ Form::select('No of Containers', [
                        'Submitted' => 'Submitted',
                        'Not-Submitted' => 'Not-Submitted',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Date</label><br>
                        {!! Form::file('file', null, array('class' => 'form-control')) !!}
                    </div>
			     </div>  
                </br>
                </br> 
		        <div class="col-lg-12 col-md-12">
                    <input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
		        </div>  
		</br>
    </div>
@endsection