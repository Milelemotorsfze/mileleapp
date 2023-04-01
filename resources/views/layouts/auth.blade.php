
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
    <body>
        @include('partials.body')       
        <div id="app">
            <main class="py-4">
                <div class="container">
                    <div class="auth-page">
                        <div class="container-fluid p-0">
                            <div class="row g-0">
                                <div class="auth-full-page-content d-flex p-sm-5 p-4 blocktext">
                                    <div class="w-100 border-design">
                                        <div class="d-flex flex-column h-100">
                                        </div>
                                        <div class="mb-4 mb-md-5 text-center">
                                            <a href="/" class="d-block auth-logo">
                                            </a>
                                        </div>
                                        <div class="auth-content my-auto">
                                            @yield('content')
                                        </div>
                                        <div class="mt-4 mt-md-5 text-center">
                                            <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Powered By <i class="mdi mdi-heart text-danger"></i> Milele</p>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end auth full page content -->
                        </div> <!-- end row -->
                    </div> <!-- end container fluid -->    
                </div>
            </main>
        </div>
        <!-- JAVASCRIPT -->
        @include('partials.vendor-scripts')
        <!-- password addon init -->
        <script src="{{ asset('js/pages/pass-addon.init.js') }}"></script>
    </body>
</html> 