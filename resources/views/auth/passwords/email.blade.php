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
                                            @if (session('status'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                            @endif

                                            <!-- <p class="text-muted mt-2">Sign in to continue to Milele.</p> -->
                                        </div>
                                        <!-- <form class="custom-form mt-4 pt-2" action="/login"method="post"> -->
                                        <form method="POST" action="{{ route('password.email') }}">
                                         @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Email Address</label>
                                                <!-- <input type="text" class="form-control" id="username" placeholder="Enter username" name="username"> -->
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                          
                                            <div class="mb-3">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                {{ __('Send Password Reset Link') }}
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