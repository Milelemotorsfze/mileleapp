<!doctype html>
<html lang="en">
    <head>
        @include('partials/head-css')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
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
        @stack('scripts')
        <!-- dropzone js -->
        <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {
        ClassicEditor
          .create(document.querySelector('#editor'))
          .catch(error => {
            // console.error(error);
          });
});
        </script>
    </body>
</html>















