@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit Role</h4>
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('roles.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!} 
                <div class="row">                                                
                    <div class="col-lg-12 col-md-12">
                        <label for="basicpill-firstname-input" class="form-label">Name : </label>
                        {!! Form::text('name', old('name', $role->name), ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <br/> 
                            <h3><strong>Permission:</strong></h3>
                            <br/>
                            @foreach($modules as $module)
                                <h4>{{$module->name}}</h4>
                                <br/>                                        
                                <div class="table-responsive">
                                    <table class="table table-striped table-editable table-edits table">
                                        <thead>
                                            <tr>
                                                <th>Check</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <div hidden>{{$i=0;}}</div>
                                            @foreach($module->permissions as $permission)      
                                                <tr data-id="1">
                                                    <td> 
                                                        {{ Form::checkbox('permission[]', $permission->id, in_array($permission->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                    </td>
                                                    <td>
                                                        {{ $permission->slug_name  }}                                               
                                                    </td>
                                                    <td>
                                                        {{ $permission->description  }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <br/>
                            @endforeach
                        </div>
                    </div> 
                    <div class="col-lg-12 col-md-12">
                        <input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
                    </div> 
                </div> 
            {!! Form::close() !!}
        </div> 
    </div>   
@endsection