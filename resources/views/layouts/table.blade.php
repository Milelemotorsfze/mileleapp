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
        <style>
            .related-addon-header
            {
                background-color:#5156be;
            }
            .related-addon-h4
            {
                padding-top:8px;
                padding-bottom:8px;
                text-align:center;
                color:white;
            }
            .related-addon .each-addon
            {
                background-color:#f2f2f2;
                border-style: solid;
                border-width: 1px;
                border-color: white;
                border-radius: 5px;
                margin-left:0px;
                margin-right:0px;
                margin-top:5px;
                margin-bottom:5px;
                /* padding-left:1px;
                padding-right:1px; */
                /* margin-top: 10px; */
                padding-top:10px;
                padding-bottom:10px;
            }
            .related-addon input
            {
                padding-top:0px;
                padding-bottom:0px;
                padding-right:0px;
                padding-left:0px;
                /* height:50%; */
            }
            .related-label
            {
                padding-top:0px;
                padding-bottom:0px;
            }
           
            /* .related-addon .related-input-div
            {
                margin-top:0px;
                margin-bottom:0px;
                margin-right:0px;
                margin-left:0px;
            } */
          .list2
          {
            margin-right:10px;
                margin-left:10px;
          }
          .labellist
          {
            border-style: solid;
                border-width: 1px;
                border-color: #5156be;
                border-radius: 5px;
              
          }
          .labeldesign
          {
            background-color:#6266c4;
            color:white;
            border-color: white;
          }
          .databack1{
            background-color:#e6e6ff;
            border-color: white;
          }
          .databack2{
            background-color:#f2f2f2;
            border-color: white;
          }
          /* #addonListTable{
            display: none;
          } */
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