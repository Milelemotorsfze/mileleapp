<!doctype html>
<html lang="en">
    <head>
        @include('partials/head-css')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
        <style>
     /* .modal-content {
            position:fixed;
            top: 50%;
            left: 50%;
            width:30em;
            height:18em;
            margin-top: -9em;
            margin-left: -15em;
            border: 2px solid #e3e4f1;
            background-color: white;
        }
        .modal-title {
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .modal-paragraph {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        .modal-button-class {
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
        } */
        .icon-right {
            z-index: 10;
            position: absolute;
            right: 0;
            top: 0;
        }
</style>
    </head>
    <body data-layout="horizontal">
        <div id="layout-wrapper">
            @include('partials/horizontal')
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
                $('.close').on('click', function() {
                    $('.alert').hide();
                })

        ClassicEditor
          .create(document.querySelector('#editor'))
          .catch(error => {
            // console.error(error);
          });
});
        </script>

    </body>
</html>
