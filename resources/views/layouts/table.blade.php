<!doctype html>
<html lang="en">
    <head>
    @include('partials/head-css')
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
        <meta charset="utf-8">
        <meta name="csrf-token" content="content">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
			// datatables
            $('#dtBasicExample').DataTable();
            $('#dtBasicSupplierInventory').DataTable()
            $('#dtBasicExample1').DataTable();
            $('#dtBasicExample2').DataTable();
            $('#dtBasicExample3').DataTable();
            //    
        }); 
        </script>
       
    </body>
</html>
