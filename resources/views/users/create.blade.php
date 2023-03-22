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
                                            <!-- <input type="text" class="form-control" id="basicpill-firstname-input" name="username"> -->
                                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                        </div>
										<div class="col-lg-6 col-md-6">
                                            <label for="basicpill-firstname-input" class="form-label">Email : </label>
                                            <!-- <input type="text" class="form-control" id="basicpill-firstname-input" name="password"> -->
                                            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <label for="basicpill-firstname-input" class="form-label">Password : </label>
                                            <!-- <input type="text" class="form-control" id="basicpill-firstname-input" name="password"> -->
                                            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <label for="basicpill-firstname-input" class="form-label">Confirm Password : </label>
                                            <!-- <input type="text" class="form-control" id="basicpill-firstname-input" name="password"> -->
                                            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                                        </div>
                                            <!-- <div class="col-lg-4 col-md-6">
                                            <label for="basicpill-firstname-input" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="basicpill-firstname-input" name="name">
                                            <input type="hidden" class="form-control" id="basicpill-firstname-input" name="created_by" value="">
                                            <input type="hidden" class="form-control" id="basicpill-firstname-input" name="status" value="New">
										   </div> -->
										   
										<div class="col-lg-4 col-md-4">
                                                <label for="choices-single-default" class="form-label font-size-13 text-muted">Role : </label>
                                                {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
                                        </div>
										<!-- <div class="col-lg-4 col-md-6">
                                            <label for="choices-single-default" class="form-label font-size-13 text-muted">Permission</label>
                                                <select class="form-control" id="choices-single-default" placeholder="This is a search placeholder" name="permission">
                                                    <option value="View Only">View Only</option>
													<option value="Full Edit">Full Edit</option>
                                                    <option value="Department Edit">Department Edit</option>
                                                </select>
                                        </div> -->
										</div>   
										</div>   
										<div class="col-lg-12 col-md-12">
								<input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
								</div>  
								{!! Form::close() !!}
								</br>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        @include('partials/footer') 
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->


@include('partials/right-sidebar') 

<!-- JAVASCRIPT -->
@include('partials/vendor-scripts') 

<!-- dropzone js -->
<script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>