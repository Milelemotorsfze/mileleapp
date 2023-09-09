@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create New User</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('users.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
        {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
            <div class="row">
			</div>  
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row"> 
					<div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Name : </label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>                    
					<div class="col-lg-6 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Email : </label>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <label for="basicpill-firstname-input" class="form-label">Password : </label>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <label for="basicpill-firstname-input" class="form-label">Confirm Password : </label>
                        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                    </div>
					<div class="col-lg-4 col-md-4">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Role : </label>
                        {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
                        <!-- <h4>Role :</h4> -->
            <!-- <select class="country"
                    multiple="true"
                    style="width: 200px;">
                    @foreach($roles as $role)
                <option value="{{$role}}">{{$role}}</option>
                @endforeach
            </select> -->
                    </div>
                    
                    <div class="col-lg-2 col-md-2">
                        <label class="form-check-label" for="sales_rap">Sales RAP</label>                        
                        <input class="form-check-input" name="sales_rap" type="checkbox" id="sales_rap" value="yes">
                    </div>
                    <!-- <form> -->
            <!-- <h4>Role :</h4>
            <select class="country"
                    multiple="true"
                    style="width: 200px;">
                <option value="1">India</option>
                <option value="2">Japan</option>
                <option value="3">France</option>
            </select> -->
            <!-- <h4>Selections using Chosen</h4>
            <select class="country1" 
                    multiple="true" 
                    style="width: 200px;">
                <option value="1">India</option>
                <option value="2">Japan</option>
                <option value="3">France</option>
            </select> -->
        <!-- </form> -->
				</div>   
			</div>   
			<div class="col-lg-12 col-md-12">
				<input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
			</div>  
		{!! Form::close() !!}
		</br>
    </div>
@endsection