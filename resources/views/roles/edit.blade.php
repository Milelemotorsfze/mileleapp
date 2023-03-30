<!doctype html>
<html lang="en">
    <head>

        @include('partials/head-css') 
    </head>
    <body data-layout="horizontal">
        <!-- Begin page -->
        <div id="layout-wrapper">
            @include('partials/horizontal') 
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- end page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
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
                                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="form-group">
                                                    <h3><strong>Permission:</strong></h3>
                                                    <br/>
                                                    @foreach($modules as $module)
                                                        <h4>{{$module->name}}</h4>
                                                        <br/>
                                                        <div class="row">
                                                            @foreach($module->permissions as $permission)                                                    
                                                                <div class="col-xs-12 col-sm-3 col-md-2">                                                                   
                                                                    <label>{{ Form::checkbox('permission[]', $permission->id, in_array($permission->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                                {{ $permission->slug_name }}
                                                            </label>
                                                                </div>
                                                            @endforeach
                                                        </div> 
                                                        <br/>
                                                    @endforeach
                                                </div>
                                            </div> 
                                            <div class="col-lg-12 col-md-12">
                                                <input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
                                            </div>  
                                        {!! Form::close() !!}
                                    </br>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- container-fluid -->
                </div> <!-- End Page-content -->
                @include('partials/footer') 
            </div> <!-- end main content-->
        </div> <!-- END layout-wrapper -->
    @include('partials/right-sidebar') 
    <!-- JAVASCRIPT -->
    @include('partials/vendor-scripts') 
    <!-- dropzone js -->
    <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>