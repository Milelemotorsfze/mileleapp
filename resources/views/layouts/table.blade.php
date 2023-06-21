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
        <script src="{{ asset('js/app.js')}}"></script>
        <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script>
        $(document).ready(function ()
        {

            alertify.set('notifier','position', 'top-right','delay', 40);
			// datatables
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
            $('#new-LOI-table').DataTable();
            $('#supplier-approved-LOI-table').DataTable();
            $('#milele-approved-LOI-table').DataTable();
            $('#supplier-rejected-LOI-table').DataTable();
            $('#milele-partial-approved-LOI-table').DataTable();
            $('#vehicle-pictures-table').DataTable();
            $('#PFI-table').DataTable();
            $('#warranty-table').DataTable();
            $('#warranty-brands-table').DataTable();
            $('#purchase-price-histories-table').DataTable();
            $('#pending-selling-price-histories-table').DataTable();
            $('#approved-selling-price-histories-table').DataTable();
            $('#rejected-selling-price-histories-table').DataTable();

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
            $('input[type=date]').on('change',function(){
                $(this).valid();
            });
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
