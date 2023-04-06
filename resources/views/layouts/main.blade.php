<!doctype html>
<html lang="en">
    <head>
        @include('partials/head-css') 
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
                border-style: solid;
                border-width: 1px;
                border-color: #5156be;
                border-radius: 5px;
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
          
        </style>
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
        <script type="text/javascript">
            // display selected addon image
            function readURL(input) 
            {        
                if (input.files && input.files[0]) 
                {
                    var reader = new FileReader();
                    reader.onload = function (e) 
                    {
                        $('#blah').css('visibility', 'visible');
                        $('#blah').attr('src', e.target.result).width('100%').height('#blah'.width);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            // jquery
            $(document).ready(function()
            {   
                // hide addon image tag when page reload 
                $('#blah').css('visibility', 'hidden');
                // add row
                var i=1;  
                var j=1;
                $('#add').click(function()
                {  
                    var title = $("#title").val();
                    i++;  
                    var title = $("#title1").val();
                    i++;  
                    var html = '';
                    html += '</br>';
                    html += '<div id="row'+i+'" class="dynamic-added">';
                    html += '<div class="row">';
                    html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
                    html += '<div class="row">';
                    html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
                    html += '<input list="cityname1" id="addon_name" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="brand[]" placeholder="Choose Brand" value="" required autocomplete="addon_name" autofocus>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
                    html += '<div class="row">';
                    html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
                    html += '<input list="cityname2" id="addon_name1" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value="" required autocomplete="addon_name" autofocus>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-xxl-1 col-lg-1 col-md-2">';
                    html += '<a id="'+i+'" style="float: right;" class="btn btn-sm btn-danger btn_remove"><i class="fa fa-minus" aria-hidden="true"></i> Remove</a>';
                    html += '</div>';            
                    html += '</div>';
                    html += '</div>';
                    $('#dynamic_field').append(html);
                });  
                // remove row
                $(document).on('click', '.btn_remove', function()
                {  
                    var button_id = $(this).attr("id");   
                    $('#row'+button_id+'').remove();  
                });
                //get addon_id
                $('#submit').click(function()
                {
                    var value = $('#addon_id').val();
                    var a = $('#cityname [value="' + value + '"]').data('value');
                    $('#addon_id').val(a);
                    var value1 = $('#title').val();
                });
                $('#addon_id').change(function()
                {
                    // fetch addon existing images
                    var value =$('#addon_id').val();
                    var id = $('#cityname [value="' + value + '"]').data('value');
                    $.ajax
                    ({
                        url: '/addons/existingImage/'+id,
                        type: "GET",
                        dataType: "json",
                        success:function(data) { 
                            var html = '';
                            html += '<h1>cccccccccc</h1>';
                            $('#dynamic_field1').append(html);
                            // console.log(data);                     
                            // $('select[name="city"]').empty();
                            // $.each(data, function(key, value) {
                            // $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
                            // });
                        }
                });
                })
            }); 
        </script>
        <script type="text/javascript">
         
            $(document).ready(function() {


});
        </script>
    </body>
</html>



                        
                    
                    
                     
                    
                
            
            




