@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit User</h4>
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
        {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
            <div class="row">
			</div> 
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
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">Role : </label>
                    {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple')) !!}
                </div>
			</div>   
		    <div class="col-lg-12 col-md-12">
				<input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
			</div>  
		{!! Form::close() !!}
		</br>
    </div>
@endsection