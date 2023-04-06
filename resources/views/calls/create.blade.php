@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create New Calls</h4>
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
					<div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">date : </label>
                        {!! Form::date('date', null, array('class' => 'form-control')) !!}
                    </div>
					<div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Name : </label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Phone : </label>
                        {!! Form::number('phone', null, array('placeholder' => 'Phone','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Email : </label>
                        {!! Form::email('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                        <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Sales Person : </label>
                        {{ Form::select('sales_person', [
                        'Aymen B Bouderbala' => 'Aymen B Bouderbala',
                        'Faisal Raiz' => 'Faisal Raiz',
						'Fahad Raiz' => 'Fahad Raiz',
                        'Mohammed Azarudin Abdul' => 'Mohammed Azarudin Abdul',
                        'Paul Membwange' => 'Paul Membwange',
                        'Lincoln Mukwada' => 'Lincoln Mukwada',
                        'Abdu Khakim Mamutov' => 'Abdu Khakim Mamutov',
                        'Hanif Mohideen Afiq A K' => 'Hanif Mohideen Afiq A K',
                        'Ayman Abdel Rafe' => 'Ayman Abdel Rafe',
                        'Raymond Tichaona Chikoki' => 'Raymond Tichaona Chikoki',
                        'Muath A A Kullab' => 'Muath A A Kullab',
                        'Belal Reyad Batal' => 'Belal Reyad Batal',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
                        {{ Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => '3']) }}
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
@endsection