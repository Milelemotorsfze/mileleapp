<!doctype html>
<html lang="en">
    <head>
        @include('partials/head-css')
        <meta name="csrf-token" content="{{ csrf_token() }}" charset="UTF-8">
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
        <script src="{{ asset('js/custom/intlTelInput.min.js') }}"></script>
        <!-- CHANGE CDN TO LOCAL PATH --><!-- add new js/custom/ckeditor.js -->
        <!-- <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script> -->
        <script src="{{ asset('js/custom/ckeditor.js') }}"></script>
        <!-- CHANGE CDN TO LOCAL PATH --> <!-- INCLUDE THE EXISTING jquery.min.js --> 
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
        <!-- CHANGE CDN TO LOCAL PATH --> <!-- add new js/custom/jquery.validate.min.js --> 
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script> -->
		<script src="{{ asset('js/custom/jquery.validate.min.js') }}"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script> -->
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
     /* @media only screen and (max-width: 600px) {
     @media only screen and (max-width: 600px) {
         .sm-mt-20 {
             margin-top: 20px;
         }
         .sm-mt-3 {
             margin-top: 10px;
         }
     }
     @media only screen and (max-width: 1200px) {
         .md-mt-26{
             margin-top: 26px;
         }
     }
     @media only screen and (max-width: 1200px) {
         .md-mt-26{
             margin-top: 26px;
         }
     } */
</style>
    </head>
    <body data-layout="horizontal">
        <div id="layout-wrapper">
        @include('partials.horizontal')
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
                alertify.set('notifier','position', 'top-right','delay', 40);
                $('.close').on('click', function() {
                    $('.alert').hide();
                })
        ClassicEditor
          .create(document.querySelector('#editor'))
          .catch(error => {
            // console.error(error);
          });
        $('input[type=file]').on('change',function(){
            // $(this).valid(); // COMMENTED BECAUSE IT MAKE error in console when onchange of file
        });
        $('input[type=date]').on('change',function(){
            $(this).valid();
        });
        });
        </script>
    </body>
</html>
