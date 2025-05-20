<!doctype html>
<html lang="en">
    <head>
    @include('partials/head-css')
        <!-- CHANGE CDN TO LOCAL PATH --> <!-- INCLUDE THE EXISTING jquery.min.js --> 
    	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
        <!-- CHANGE CDN TO LOCAL PATH --> <!-- add new js/custom/jquery.validate.min.js --> 
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script> -->
		<script src="{{ asset('js/custom/jquery.validate.min.js') }}"></script>
        <!-- CHANGE CDN TO LOCAL PATH --> <!-- remove from here because 4.1.0 is already included in vendor-scripts --> 
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script> -->
        <meta charset="utf-8">
        <!-- <meta name="csrf-token" content="content"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            .btn-soft-blue{
                background-color: #1bb4e3;
                color: #FFFFFF;
            }
            .btn-soft-blue:hover{
                color: #FFFFFF;
            }
            .btn-soft-green{
                background-color: #1bbc9b;
                color: #FFFFFF;
            }
            .btn-soft-green:hover{
                color: #FFFFFF;
            }
            .btn-soft-violet{
                background-color: #5c61af;
                color: #FFFFFF;
            }
            .btn-soft-violet:hover{
                color: #FFFFFF;
            }
            .btn-dark-blue{
                background-color: #4275e1;
                color: #FFFFFF;
            }
            .btn-dark-blue:hover{
                color: #FFFFFF;
            }
            .approvalBtnClass {
    display: flex;
}
.approvalBtnClass button {
	margin-left:2px!important;
}
        </style>
    </head>
    <body data-layout="horizontal">
        <div id="layout-wrapper">
        @include('partials.horizontal')
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
        @stack('scripts')
        <script src="{{ asset('libs/table-edits/build/table-edits.min.js')}}"></script>
        <script src="{{ asset('js/pages/table-editable.int.js')}}"></script>
        <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('js/app.js')}}"></script>
        <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script>
        $(document).ready(function ()
        {
            alertify.set('notifier','position', 'top-right','delay', 40);
			// datatables
            $('.my-datatable').DataTable();
            $('#dtBasicExample').DataTable();
            $('#dtBasicSupplierInventory').DataTable();
            $('#dtBasicExample1').DataTable();
            $('#dtBasicExample2').DataTable();
            $('#dtBasicExample3').DataTable();
            $('#dtBasicExample4').DataTable();
            $('#dtBasicExample5').DataTable();
            $('#dtBasicExample6').DataTable();
            $('#dtBasicExample7').DataTable();
            $('#dtBasicExample8').DataTable();
            $('#vehicle-pictures-table').DataTable();
            $('#loi-criteria-country-table').DataTable();
            $('#PFI-table').DataTable();
            $('#warranty-table').DataTable();
            $('#warranty-brands-table').DataTable();
            $('#purchase-price-histories-table').DataTable();
            $('#pending-selling-price-histories-table').DataTable();
            $('#approved-selling-price-histories-table').DataTable();
            $('#rejected-selling-price-histories-table').DataTable();
            $('#variant-without-price-table').DataTable();
            $('#variant-with-price-table').DataTable();
            $('#rejected-selling-price-histories-table').DataTable();
            $('#vendor-table').DataTable();
            $('#vehicle-with-price-table').DataTable();
            $('#vehicle-price-histories-table').DataTable();
            $('#accessories-table').DataTable();
            $('#spare-parts-table').DataTable();
            $('#kits-table').DataTable();
            $('#permission-table').DataTable();
            $('#module-table').DataTable();
            $('#pending-hiring-requests-table').DataTable();
            $('#approved-hiring-requests-table').DataTable();
            $('#closed-hiring-requests-table').DataTable();
            $('#on-hold-hiring-requests-table').DataTable();
            $('#cancelled-hiring-requests-table').DataTable();
            $('#rejected-hiring-requests-table').DataTable();
            $('#deleted-hiring-requests-table').DataTable();
            $('#addon-pending-selling-prices').DataTable({
                "pageLength": 5,
                "dom": 'frtip',
            });
            $('#addon-without-selling-prices').DataTable({
                "pageLength": 5,
                "dom": 'frtip',
            });
            $('#table-latest-accessories').DataTable({
                "pageLength": 5,
                "dom": 'frtip',
            });
            $('#table-latest-spare-parts').DataTable({
                "pageLength": 5,
                "dom": 'frtip',
            });
            $('#table-latest-kits').DataTable({
                "pageLength": 5,
                "dom": 'frtip',
            });
            $('#variant-update-table').DataTable();
            // $('#suppliersList').DataTable();
            // $('#suppliersList').on( 'click', '.modal-button', function () {
            //     var modalId = $(this).data('modal-id');
            //     ModalOpen(modalId);
            // });

            // $('#supplier-pictures-table').on( 'click', '.modal-button', function () {
            //     var modalId = $(this).data('modal-id');
            //     ModalOpen(modalId);
            // });

            // $('.modal-button').on('click', function()
            // {
            //     var modalId = $(this).data('modal-id');
            //     ModalOpen(modalId);
            // });
            function ModalOpen(modalId) {
                $('#' + modalId).addClass('modalshow');
                $('#' + modalId).removeClass('modalhide');
            }
            $('.close').on('click', function(){
                $('.modal').addClass('modalhide');
                $('.modal').removeClass('modalshow');
                $('.alert').hide();

            });
            // $('input[type=date]').on('change',function(){
            //     $(this).valid();
            // });
                    // div colour
                    // alert($('.divcolorclass').val());

                    // show addon list table

                    // $('#addonListTableButton').on('click', function()
                    // {

                    //     // $('#addonListTable').attr("hidden", true);
                    //     // $('#addonListTable').attr("hidden", false);
                    //     // $('#addonListTable').removeAttribute("hidden");
                    //     // alert('hiis');
                    //     // $('#addonListTable').style.display = "block";
                    // });
        });
        function closemodal()
            {
                $('.modal').removeClass('modalshow');
                $('.modal').addClass('modalhide');
            }
            function showAddonTable()
            {
                let addonTable = document.getElementById('addonListTable');
                addonTable.hidden = false
                let addonListTableButton = document.getElementById('addonListTableButton');
                addonListTableButton.hidden = true
                let addonbox = document.getElementById('addonbox');
                addonbox.hidden = true
                let addonBoxButton = document.getElementById('addonBoxButton');
                addonBoxButton.hidden = false
            }
            function showAddonBox()
            {
                let addonTable = document.getElementById('addonListTable');
                addonTable.hidden = true
                let addonListTableButton = document.getElementById('addonListTableButton');
                addonListTableButton.hidden = false
                let addonbox = document.getElementById('addonbox');
                addonbox.hidden = false
                let addonBoxButton = document.getElementById('addonBoxButton');
                addonBoxButton.hidden = true
            }
        </script>

    </body>
</html>
