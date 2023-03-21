<!doctype html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Login | MILELE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Milele" name="description" />
        <meta content="Milele" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        @include('partials.head-css')
        <style>
            div.blocktext { 
                margin-left: auto; 
                margin-right: auto; 
                width: 40em 
            }
            div.border-design{
                padding-left: 20px;
                padding-right: 20px;
                padding-bottom: 20px;
                margin-top: auto;
                margin-bottom: auto;
                border:#5156BE; 
                border-width:2px; 
                border-style:solid;
            }
        </style>
    </head>
    @include('partials.body')
    <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <!-- <div class="col-xxl-3 col-lg-4 col-md-5"> -->
                        <div class="auth-full-page-content d-flex p-sm-5 p-4 blocktext">
                            <div class="w-100 border-design">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5 text-center">
                                        <a href="/" class="d-block auth-logo">
                                        </a>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Reset Password</h5>
                                            

                                            <!-- <p class="text-muted mt-2">Sign in to continue to Milele.</p> -->
                                        </div>
                                        <form method="POST" action="{{ route('password.update') }}">
                                         @csrf

                                            <div class="mb-3">
                                                <label class="form-label">Email Address</label>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Password</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <button class="btn btn-light ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Confirm Password</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="input-group auth-pass-inputgroup">
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <button class="btn btn-light ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Reset Password') }}
                                            </button>
                                           
                                
                                            </div>
                                            
                                        </form>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Powered By <i class="mdi mdi-heart text-danger"></i> Milele</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    <!-- </div> -->
					
                  
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>


        <!-- JAVASCRIPT -->
        @include('partials.vendor-scripts')
        <!-- password addon init -->
        <script src="{{ asset('js/pages/pass-addon.init.js') }}"></script>

    </body>

</html>