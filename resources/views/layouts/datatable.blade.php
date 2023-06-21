<!doctype html>
<html lang="en">
<head>
@include('partials/head-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</head>
<body data-layout="horizontal">
    <div id="layout-wrapper">
    @include('partials.horizontal')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
            @yield('content')
            </div>
        </div>
        @include('partials/footer')
    </div>
</div>
@include('partials/vendor-scripts')
@stack('scripts')
<script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
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