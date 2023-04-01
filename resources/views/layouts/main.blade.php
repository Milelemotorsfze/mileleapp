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
                                    @yield('content')
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