<!doctype html>
<html lang="en">
    <head>
    @include('partials/head-css') 
    <style>
        .modal-content {
            position:fixed;
            top: 50%;
            left: 50%;
            width:30em;
            height:18em;
            margin-top: -9em; /*set to a negative number 1/2 of your height*/
            margin-left: -15em; /*set to a negative number 1/2 of your width*/
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
        }
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
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials/footer') 
            </div>
        </div>    
        @include('partials/right-sidebar') 
        @include('partials/vendor-scripts') 
        <script src="{{ asset('libs/table-edits/build/table-edits.min.js')}}"></script>
        <script src="{{ asset('js/pages/table-editable.int.js')}}"></script>
        <script src="{{ asset('js/app.js')}}"></script>
        <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script>
        $(document).ready(function () 
        {
            $('#dtBasicExample').DataTable();
            $('#dtBasicExample1').DataTable();
            $('#dtBasicExample2').DataTable();
            $('#dtBasicExample3').DataTable();
            $('.modal-button').on('click', function()
            {
                var modalId = $(this).data('modal-id');
                $('#' + modalId).addClass('modalshow');
                $('#' + modalId).removeClass('modalhide');
                console.log('Modal Show');
                });
                $('.close').on('click', function(){
                    $('.modal').addClass('modalhide');
                    $('.modal').removeClass('modalshow');
                    // $('.modal').hide();
                    console.log('Modal Hidden from close button');
                    });
        });
        function closemodal()
            {    
                $('.modal').removeClass('modalshow');
                $('.modal').addClass('modalhide');
            }
        </script>
    </body>
</html>